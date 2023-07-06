<?php
  $with_domain_status = 0;
  $user_id = auth()->user()->id;
  $user_role = auth()->user()->role;
  $role_id = DB::table('roles')->where('name',$user_role)->first()->id;
  $role_permission = DB::table('role_permisiions')->where('role_id',$role_id)->pluck('content_name')->toArray();
 // file_put_contents('role.txt',json_encode($role_permission));


?>
