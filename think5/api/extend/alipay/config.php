<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2017082408354802",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEA2DkqzoOKMsL1VDQAdeed41EaOfG4OOfHP9ROl1rV5yarg/pR
GKEfkXtctIcEwgla/vScSoJhDi8+S8OJ5y9hyKPtzcvdUeVHMvZgyThv4oZyPZHr
MxGya0yhlK2goKMJHlzFJCOY97Uzx8SGCZhRqaijfc+ihLSZLyZvaojbvqdz2hNT
ZsiCr6XTAwvq+6dJ7Hhy6Lm1Jw9mySXi9zEkmLhHbP0JuNKqXG/bMKOW/I0qth9T
30xFuisEctv8tABulTRIvXNzWQceqEK3522sP4qHY+dlo5/pO59qjf5SteTYiBMq
lvoa4DaShpAUp74o7H/LBUgrWEFAPvh+QndTHQIDAQABAoIBAFJivd/AGQksZaO2
yLIkFXHGtjJ72pC8J5w3fdaHwtb3UgTQfNPgmJGrKnCSvYpdXDGm7GIreWWZNKhT
khjnYd+8LmDqm6KXtOBDJi+ldGQgjCjPj4l+5beilDJ9UaXDWTcba/ZQJfnELj2g
sN25DuyRF/JZCfh8g52GjI9QJEJpAZozFmNBT8n6ICqB/yUr/buWZ5GuNtaDFzfz
uxAG2OK0/D9cD8s6iE55VxjuCKpnpri+Y+7S6jt1QDskH2BPrwfVMHRNzWDm0W08
ZXeNolJ2gcjasPPl0RoY6WKPG9mfTJ+z2lTesY1WhE2wbpH5rccNkbuVOZqBuh57
dgsOG8ECgYEA/c18w1saOyZUmAQhS3aZSFhVa+8JJbb+x2Rzq4Yusd39gyVDruYo
THXcqbYVPFuoF5+c8T7ZDVp0e9AnVCRpnlTpOhnjg1ev8OFGO8aZ8BeMINAXMAtI
+G6LxT+fdsV8iZFWqo5CJ18wojMaZqbFYKbX25IVMz57QnTXJc+fCm0CgYEA2hhk
KIn8t1nPmAPTUvo2QY4GeWAWerNzi8ASYRENgsc3uj1yz1fLEJnyGXSuLhb7rb5x
xS+uSoFycaBVeX1xUZ1ObVdBOgwk3pIT/DnkVYYGnsOqDcVL6NtTpLJKaaS8mNQk
PRowAbu5jjvaL8Sgad17o519FAJS7Y8Fq5YO/XECgYEAqqQhETvMbB5+W+LS++jW
cFSrwjDp5oidzjPUrWs/M+l8TFOeqRYn3BiWbyh0KKu7XX68R1spFhhOHvy5pJIC
zShr0tubIzCuo3bjAMerskgyt9EQ9gVwX0/7+3emYHWxINEuAug4xD309ekEBCZ2
qGAk24Thah3FQ85I5Swt0xUCgYAiyf1AEjLDtInOv913wP3imxshRViQngtyYWMN
JME4+TpEdQTZWjHEJipeMSSPqY7f+h7/y0lMDTYKNf8sb/whfqxB+Mco0UMYVcjm
VP29PrHTpXZ819nx/PpsrRv9mg+TeVOGg6Tgwecpbxaww/aqrG/Ke5a2GGDOECBh
bIBboQKBgAF68ltTF+tfbDUXq+LHLsqzp2F1Bxj9HKpSaAMEkUV70CkathQswuGj
wabUuSEZfQKxjf/TPdWQ2jDZ3HHMV0Jq//MtYvNdx2Q+qKfnlXQrnVrENzmqFfwk
uMrNL2BlUxooxBwCFhtj04LoSLRo6EM4GWDwShB/EUrUa/1pEM3f",
		
		//异步通知地址
		'notify_url' => "http://www.chjdwl.xyz/alipayNotify",
		
		//同步跳转
		//'return_url' => "http://www.chjdwl.xyz/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhNvojSoGEAjlnl1aKkhRkaUk/n7SQh4sgPqRgFATOkvx/J75AbmCvzD00GFdvrgz6soK4wB5ozr4r0NraISOPHHG9yJpl3YmXvgBFzNOG5PrZ3+3LSOVi8GYXe1Rc0KeUPgmYX9ZycNsRvZeS8e4GDrhESD7PaXGCiUN4BJKxsAVvbBQyf01DKPPAMYDKagu/2gtxY/ZVpOs4d75pQVZIr4vHhBD/MJBxNNz7ZWhUKHk7GRlvOtmQhDRhsIiucB3qvQIgnUp07qRGALwgVUesSnWyTLC/AyZteS8h7TlAYaKc2BBMtUqSyLF/CmugJNOjxehSXYLa8nE+h91jQ+QjQIDAQAB",
);