<?php

namespace App\Http\Controllers;


use App\License;
use App\Token;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    /**
     * Status
     * -1 未知错误
     * 0 正常
     * 1 错误的许可证Key
     * 8 许可证已激活
     * 9 许可证未激活
     */

    /**
     * 生成License
     * @param string $remark 备注
     * @param int $status 状态
     * @param Carbon $expire_at 过期时间
     * @param array $beneficiary 授权人
     * @param int $num 生成数量
     * @return array|bool
     */
    public function makeLicense(string $remark, int $status, Carbon $expire_at, array $beneficiary, int $num = 1)
    {
        $license = [];
        for ($i = 1; $i <= $num; $i++) {
            $license[] = [
                'uuid' => Str::uuid(),
                'key' => Str::random(26) . date('ymd'),
                'remark' => $remark,
                'status' => $status,
                'expire_at' => $expire_at,
                'beneficiary' => json_encode($beneficiary),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }
//        dd($license);
        try {
            DB::table('licenses')->insert($license);
        } catch (\Exception $exception) {
            Log::error('makeLicense Error', [$exception, $license]);
            return false;
        }

        return $license;
    }


    /**
     * 创建返回Arr
     * @param array $data 数据
     * @param string $msg 注释
     * @param int $code 错误代码
     * @return array
     */
    public function makeResultArr(array $data = [], string $msg = "Success", int $code = 0)
    {
        return [
            'status' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
    }

    /**
     * 验证许可证是否存在
     * @param Request $request
     * @return JsonResponse
     */
    protected function validateLicense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license' => 'required|string|exists:licenses,key'
        ]);

        if ($validator->fails()) {
            return response()->json($this->makeResultArr([], 'License Error', 1));
        }
    }

    /**
     * 激活License
     * @param Request $request
     * @return JsonResponse
     */
    public function activation(Request $request)
    {
        $this->validateLicense($request);
        $license =   License::where('key',$request['license'])->first();
        if ($license->status != 1){
            return response()->json($this->makeResultArr([], 'error,License activated', 8));
        }
        License::where('key',$request['license'])->update(['status' => 2]);
        return response()->json($this->makeResultArr([], 'Success', 0));

    }

    /**
     * 取消
     * @param Request $request
     * @return JsonResponse
     */
    public function cancel(Request $request)
    {
        $this->validateLicense($request);
        $license =   License::where('key',$request['license'])->first();
        if ($license->status != 2){
            return response()->json($this->makeResultArr([], 'error,License inactivated', 9));
        }
        License::where('key',$request['license'])->update(['status' => 3]);
        return response()->json($this->makeResultArr([], 'Success', 0));
    }


    /**
     * 查询
     * @param Request $request
     * @return JsonResponse
     */
    public function inquire(Request $request)
    {
        $this->validateLicense($request);
        $license = License::where('key', $request['license'])->first();
        return response()->json([
            'key' => $license->key,
            'expire_at' => $license->expire_at,
            'status' => $license->status,
            'beneficiary' => json_decode($license->beneficiary),
        ]);
    }

    /**
     * 生成授权码
     * @param Request $request
     * @return JsonResponse
     */
    public function generate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required|string|exists:token',
            'remark' => 'nullable|string|max:191',
            'expire_at' => 'nullable|date',
            'beneficiary' => 'nullable',
            'num' => 'nullable|max:200',
        ]);

//
//        dd($validator->fails());
        if ($validator->fails()) {
            switch ($validator->fails()) {
                case "token":
                    return response()->json($this->makeResultArr([], 'Token Error', 1));
                    break;
                default:
                    return response()->json($this->makeResultArr([], 'error,place send error log to yf@rbq.ai', -1));
            }
        }

        $license = $this->makeLicense(
            $request['remark'] ?? "",
            1,
            Carbon::make($request['expire_at']) ?? Carbon::now()->addYears(10),
            $request['beneficiary'] ?? [],
            $request['num'] ?? 1
        );

//        dd($license);
        if ($license){
            return response()->json($this->makeResultArr([$license], 'Success', 0));
        }else{
            return response()->json($this->makeResultArr([], 'Fail', -1));
        }

    }
    //
}
