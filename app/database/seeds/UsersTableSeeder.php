 <?php

 class UsersTableSeeder extends Seeder {
 	public function run() {
 		DB::table('users')->delete();

 		$users = array(
			array(
				'name' => 'Michael',
				'password' => Hash::make('michael'),
				'email' => 'michaelycus@gmail.com'
			)
		);

 		DB::table('users')->insert($users);
 	}
 }