<?php
return array(
	'feed' => 'site/feed',
	"registration" => "site/reg",
	"autorization" => "site/autoriz",
	"social/([0-9]+)/()" => "link/social/$1/$2",
	"@([0-9]+)" => "link/profile/$1",

	'login/?()' => 'user/login/$1', 
	'ajax/()' => 'site/ajax/$1'
);
