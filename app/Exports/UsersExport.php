<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Retrieve user data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::select([
            'image_url', 'name', 'email', 'phone_number',
            'gender', 'birth_date', 'address', 'role',
            'created_at', 'updated_at'
        ])->get();
    }

    /**
     * Define the headers for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Photo Profile', 'Name', 'Email', 'Phone Number',
            'Gender', 'Birth Date', 'Address', 'Role',
            'Created At', 'Updated At'
        ];
    }

    /**
     * Map the user data for export.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->image_url ? url($user->image_url) : 'No Image',
            $user->name ?? '-',
            $user->email ?? '-',
            $user->phone_number ?? '-',
            ucfirst($user->gender) ?? '-',
            optional($user->birth_date)->toFormattedDateString() ?? '-',
            $user->address ?? '-',
            ucfirst($user->role) ?? '-',
            optional($user->created_at)->format('d-m-Y H:i:s') ?? '-',
            optional($user->updated_at)->format('d-m-Y H:i:s') ?? '-',
        ];
    }
}
