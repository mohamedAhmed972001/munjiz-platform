<?php

return [
  'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register', 'logout'],
  'allowed_methods' => ['*'],
  
  // هنا نحدد رابط الـ React بدقة بدلاً من '*'
  'allowed_origins' => ['http://localhost:5173'], 
  
  'allowed_origins_patterns' => [],
  'allowed_headers' => ['*'],
  'exposed_headers' => [],
  'max_age' => 0,
  
  // ضروري جداً لعمل الـ Sessions
  'supports_credentials' => true, 
];
