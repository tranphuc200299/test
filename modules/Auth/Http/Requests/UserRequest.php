<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed user
 */
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule['full_name'] = 'required|max:50';
        $rule['user_name'] = ['required', 'max:50',  Rule::unique('ms_users', 'user_name')->ignore($this->user)->withoutTrashed()];
        $rule['password'] = 'required|confirmed|min:6';
        $rule['role_id'] = 'required';

        if ($this->route('user')) {
//            unset($rule['user_name']);
            unset($rule['password']);
            unset($rule['role_id']);
        }

        return $rule;
    }

    public function attributes()
    {
        return [
            'full_name' => trans('auth::user.full_name'),
            'user_name' => trans('core::common.user_name'),
            'password' => trans('core::common.password'),
            'role_id' => trans('core::common.role'),
        ];
    }

    public function messages()
    {
        return [
            'full_name.max' => 'A title is required',
            'full_name.required' => 'この項目は入力必須です。',
            'user_name.required' => 'この項目は入力必須です。',
            'user_name.unique' => 'ユーザー名は既に存在しています。',
            'password.confirmed' => 'パスワードと確認用パスワードが一致しません。',
            'password.min' => 'パスワードは6文字以上入力してください。',
        ];
    }
}
