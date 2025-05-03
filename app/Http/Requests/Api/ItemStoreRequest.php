<?php

namespace App\Http\Requests\Api;

use App\Enums\Api\PostTypeEnum;
use App\Models\Category;
use App\Models\Village;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ItemStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:30'],
            'description' => ['required', 'string', 'min:3', 'max:255'],
            'category_id' => ['required', Rule::exists(Category::class, 'id')],
            'village_id' => ['required', Rule::exists(Village::class, 'id')],
            'itemImages' => ['required', 'array', 'max:2', 'min:2'],
            'itemImages.*' => ['required', File::image()->min('5kb')->max('6mb')],
            'post_type' => ['required', 'string', Rule::enum(PostTypeEnum::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a title for the item.',
            'title.min' => 'The title must be at least :min characters.',
            'title.max' => 'The title must not exceed :max characters.',

            'description.required' => 'Please provide a description.',
            'description.min' => 'The description must be at least :min characters.',
            'description.max' => 'The description must not exceed :max characters.',

            'village_id.required' => 'Please enter the location where the item was lost or found.',
            'village_id.exists' => 'Selected location does not exist.',

            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',

            'itemImages.required' => 'Item images are required..',
            'itemImages.array' => 'Please upload multiple images at once by selecting them together..',
            'itemImages.max' => 'You can upload up to 2 images only..',
            'itemImages.min' => 'Please upload at least 2 images..',
            'itemImages.*.required' => 'Each image is required..',
            'itemImages.*.image' => 'Each file must be a valid image.',
            'itemImages.*.mimes' => 'Images must be in jpg, jpeg, png or webp format..',
            'itemImages.*.max' => 'Each image must not exceed 6MB..',

            'post_type.required' => 'Please specify if this is a lost or found item.',
            'post_type.enum' => 'The post type must be one of the allowed values.',
        ];
    }
}
