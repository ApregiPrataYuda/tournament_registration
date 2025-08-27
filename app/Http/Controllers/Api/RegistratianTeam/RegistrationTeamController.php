<?php

namespace App\Http\Controllers\Api\RegistratianTeam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\ApiResponse;
use App\Http\Requests\ValidateRegistrationTeam;
use App\Http\Resources\ResourceRegisterTeam;
use App\Http\Resources\ResourceRegisterTeamCollection;
use App\Models\Teams;
use App\Models\TeamsMember;
use App\Http\Requests\ValidatedAddRegisterTeam;


class RegistrationTeamController extends Controller
{
    protected $Teams;
    protected $TeamsMember;
    public function __construct(Teams $Teams, TeamsMember $TeamsMember) {
        $this->Teams = $Teams;
        $this->TeamsMember = $TeamsMember;
    }


    public function index(ValidateRegistrationTeam $request)  {
        $validated = $request->validated();

        $search = $validated['search'] ?? null;
        $perPage = is_numeric($validated['per_page'] ?? null) ? $validated['per_page'] : 10;
        $sortBy = $validated['sort_by'] ?? 'created_at';
        $sortDir = $validated['sort_dir'] ?? 'desc';
        $onlyDeleted = $validated['only_deleted'] ?? false;

        $query = $this->Teams
            ->select('teams.*','users.fullname as nama_captain','users.email as email_captain','users.phone_number as phone_number_captain',
            'team_members.full_name_member','team_members.phone_number_member','team_members.gender_member')
            ->leftJoin('team_members','teams.team_id','=','team_members.team_id')
            ->leftJoin('users','users.id','=','teams.captain_id')
            ->onlyDeleted($onlyDeleted)
            ->search($search)
            ->sort($sortBy, $sortDir);
        $results = $query->paginate($perPage);
        $message = $results->isEmpty() ? "Data yang Anda cari tidak ditemukan" : "Success";
        return ApiResponse::paginate(new ResourceRegisterTeamCollection($results), $message);
    }



     public function show(string $id)
        {
            $TeamsRegisDetails = $this->Teams
            ->select(
                'teams.*',
                'users.fullname as nama_captain',
                'users.email as email_captain',
                'users.phone_number as phone_number_captain',
                'team_members.full_name_member',
                'team_members.phone_number_member',
                'team_members.gender_member'
            )
            ->leftJoin('team_members','teams.team_id','=','team_members.team_id')
            ->leftJoin('users','users.id','=','teams.captain_id')
            ->where('teams.team_id', $id)
            ->get(); 

            if ($TeamsRegisDetails->isEmpty()) {
                return ApiResponse::error('ID Team not found', [
                    'team_id' => ['Data dengan ID Team tersebut tidak tersedia']
                ], 404);
            }
       return ApiResponse::success(ResourceRegisterTeam::collection($TeamsRegisDetails), 'Success ambil Team Registration detail', 200);
        }


        
    public function store(ValidatedAddRegisterTeam $request)
    {
        $data = $request->validated();

        try {
            //  Cek apakah nama tim sudah terdaftar
            $existingTeam = $this->Teams->where('team_name', $data['team_name'])->first();
            if ($existingTeam) {
                return ApiResponse::error(
                    'Nama tim sudah terdaftar. Satu tim hanya boleh memiliki 2 anggota: 1 kapten & 1 anggota.',
                    [],
                    400
                );
            }

            //  Cek apakah member sudah terdaftar di tim lain
            $existingTeamMember = $this->TeamsMember
                ->where('full_name_member', $data['full_name_member'])
                ->first();

            if ($existingTeamMember) {
                return ApiResponse::error(
                    'Nama member sudah terdaftar di tim lain.',
                    [],
                    400
                );
            }

            //  Simpan data dalam transaction
            $TeamsRegisDetails = DB::transaction(function () use ($data) {
                $user = auth()->user(); // ambil data user login

                // Simpan data tim
                $team = $this->Teams->create([
                    'team_name'       => $data['team_name'],
                    'captain_gender'  => $data['captain_gender'],
                    'captain_id'      => $user->id, // otomatis user login
                ]);

                // Simpan anggota tim
                $this->TeamsMember->create([
                    'team_id'             => $team->team_id,
                    'full_name_member'    => $data['full_name_member'],
                    'phone_number_member' => $data['phone_number_member'],
                    'gender_member'       => $data['gender_member'],
                ]);

                // Ambil detail tim beserta anggota + data kapten
                return $this->Teams
                    ->select(
                        'teams.*',
                        'users.fullname as nama_captain',
                        'users.email as email_captain',
                        'users.phone_number as phone_number_captain',
                        'team_members.full_name_member',
                        'team_members.phone_number_member',
                        'team_members.gender_member'
                    )
                    ->leftJoin('team_members', 'teams.team_id', '=', 'team_members.team_id')
                    ->leftJoin('users', 'users.id', '=', 'teams.captain_id')
                    ->where('teams.team_id', $team->team_id)
                    ->get();
            });

            return ApiResponse::success(
                ResourceRegisterTeam::collection($TeamsRegisDetails),
                'Selamat, Tim anda berhasil terdaftar untuk turnament ini (Cek email anda untuk informasi dan jadwal)',
                201
            );
        } catch (\Illuminate\Database\QueryException $e) {
            return ApiResponse::error(
                'Gagal membuat data baru (query error)',
                ['exception' => config('app.debug') ? $e->getMessage() : null],
                422
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Terjadi kesalahan saat membuat data baru',
                ['exception' => config('app.debug') ? $e->getMessage() : null],
                500
            );
        }
    }



    public function destroy(string $id)
        {
            try {
                $team = $this->Teams->findOrFail($id);

                // hapus member dulu biar rapi
                $this->TeamsMember->where('team_id', $team->team_id)->delete();

                // baru hapus team
                $team->delete();

                return ApiResponse::success(null, 'Team berhasil dihapus', 200);
            } catch (ModelNotFoundException $e) {
                return ApiResponse::error('Team tidak ditemukan', [
                    'team_id' => ['Data dengan ID Team tersebut tidak tersedia']
                ], 404);
            } catch (\Exception $e) {
                return ApiResponse::error(
                    'Terjadi kesalahan saat menghapus data',
                    ['exception' => config('app.debug') ? $e->getMessage() : null],
                    500
                );
            }
        }

}
