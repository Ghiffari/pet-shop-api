<?php

namespace App\Repositories;

use DateTimeImmutable;
use Illuminate\Http\Response;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Encoding\JoseEncoder;
use App\Http\Requests\User\LoginRequest;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Models\JwtToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lcobucci\JWT\Token\Parser;

class UserRepository implements UserRepositoryInterface
{
    public function login(LoginRequest $request, bool $admin = false): array
    {
        $result = [];

        if (!Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ])) {
            return [
                'body' => [
                    'success' => 0,
                    'data' => [
                        'token' => null
                    ]
                ],
                'code' => Response::HTTP_UNAUTHORIZED,
            ];
        }
        if($admin && !$this->validateAdminRole(Auth::user())){
            return [
                'body' => [
                    'success' => 0,
                    'data' => [
                        'token' => null
                    ]
                ],
                'code' => Response::HTTP_FORBIDDEN,
            ];
        }

        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        $signingKey   = InMemory::file(config('auth.jwt.private_key_path'));

        $now   = new DateTimeImmutable();
        $uniqueId = uniqid();
        $token = $tokenBuilder
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->identifiedBy($uniqueId)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+2 hours'))
            ->withClaim('user_uuid', Auth::user()->uuid)
            ->getToken($algorithm, $signingKey);

        JwtToken::create([
            'user_id' => Auth::user()->id,
            'unique_id' => $uniqueId,
            'token_title' => "Token authentication"
        ]);

        $this->updateUser(Auth::user(), [
            'last_login_at' => Carbon::now()
        ]);

        $result = [
            'body' => [
                'success' => 1,
                'data' => [
                    'token' => $token->toString()
                ]
            ],
            'code' => Response::HTTP_OK,
        ];

        return $result;
    }

    public function getAllUsers(Request $request): array
    {
        $users = User::whereIsAdmin(0);

        if($request->get('email')){
            $users->where('email','LIKE', "%" . $request->get('email') .  "%");
        }

        $result = [
            'body' => [
                'success' => 1,
                'data' => $users->paginate($request->limit ?? 10)
            ],
            'code' => Response::HTTP_OK,
        ];

        return $result;
    }

    public function getUserByUuid(string $uuid): ?User
    {
        return User::where('uuid',$uuid)->first();
    }

    public function updateUser(User $user, array $data): User
    {
        $updatedData = [];
        if(isset($data['last_login_at'])){
            $updatedData['last_login_at'] = $data['last_login_at'];
        }
        $user->update($updatedData);
        return $user;
    }

    private function validateAdminRole(User $user)
    {
        return $user->is_admin;
    }
}
