<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:100'],
            'tagline'          => ['required', 'string', 'max:120'],
            'description'      => ['required', 'string', 'max:5000'],
            'category_id'      => ['required', 'exists:categories,id'],
            'tags'             => ['nullable', 'array', 'max:5'],
            'tags.*'           => ['exists:tags,id'],
            'logo'             => ['nullable', 'image', 'max:2048'],
            'screenshots'      => ['nullable', 'array', 'max:5'],
            'screenshots.*'    => ['image', 'max:4096'],
            'website_url'      => ['nullable', 'url', 'max:255'],
            'demo_url'         => ['nullable', 'url', 'max:255'],
            'github_url'       => ['nullable', 'url', 'max:255'],
            'is_roast_enabled' => ['boolean'],
            'launch_type'      => ['required', 'in:now,scheduled'],
            'launch_date'      => ['required_if:launch_type,scheduled', 'nullable', 'date', 'after:today'],
        ];
    }
}
