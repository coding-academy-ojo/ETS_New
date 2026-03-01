<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Employer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   $employers=[
        [
        'email'=>'omar@01tracks.com',
        'name'=>'01tracks',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'company_id'=>2,
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'
        ],
        [
        'email'=>'biz@24online.jo',
        'company_id'=>1,
        'name'=>'24 online',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'    
        ],
        ['email'=>'moayad@4matex.com',
        'name'=>'4matex',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'SKhalifa@5ytechnology.com',
        'name'=>'5 y technology',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'] ,
        ['email'=>'Ali.Etawi@Bank-ABC.com',
        'name'=>'ABC Bank',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'] ,
        ['email'=>'admin02@abjd.store',
        'name'=>'Abjad',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'smuqattash@e2abs.com',
        'name'=>'ABS',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'alaa@e2abs.com',
        'name'=>'ABS',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'recruitments@abuodehgroup.com',
        'name'=>'Abu Odeh group',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'mmamkegh@xeleration.net',
        'name'=>'Xeleration',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'sdehmes@access2arabia.com',
        'name'=>'access 2 arabia',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'info@walleterp.com',
        'name'=>'Accountly',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'m.aljobairi@gmail.com',
        'name'=>'Accountly',
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39'],
        ['email'=>'info@asoftwares.com',
        'name'=>"ADAA\' Applications Company",
        'password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'created_at'=>'2024-04-04 09:41:39',
        'updated_at'=>'2024-04-04 09:41:39']    
    ];

        
        // foreach ($employers as $employer) {
        //     \App\Models\Employer::factory()->create( $employer);
        // }
    }
}