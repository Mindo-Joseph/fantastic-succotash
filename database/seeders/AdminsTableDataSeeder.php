<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
    	//factory(App\Models\User::class,1)->create();
        \DB::table('admins')->delete();
 
        $maps = array(
            array(
                'name' => 'Admin',
		        'email' => 'admin@cbl.com',
		        'email_verified_at' => now(),
		        'password' => '$2y$10$08DOAQL70KwfBOp0vtyoyeawUKnz9x3aZGSvEflAhauLlJp7mWVjO', // password
		        'remember_token' => Str::random(10),
            ),
        ); 
        \DB::table('admins')->insert($maps);
    }
}
