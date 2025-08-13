<?php
goto mp8TnBFd;
mp8TnBFd:
if (defined("BASEPATH")) {
	goto pG0IhUo8;
}
goto v1CWK1oa;
v1CWK1oa:exit("No direct script access allowed");
goto o3jL7xGa;
o3jL7xGa:pG0IhUo8:goto gW7lihXr;
gW7lihXr:function compress_output()
{
	goto R5b1PrlJ;
	trdNiKjw:$BU6MDXMh = array(
		">", "<", "\1", "//&lt;![CDATA[\xa\1
//]]>"
	);
	goto Tw6V4fYe;
	uBfc2r7m:$naQTsO5G = array(
		"api_external", "api_internal",
		"cron", "generate_helpers"
	);
	goto PnWdFfqv;
	LGB1wnl1:$TdNT4MlS = $CI->output->get_output();
	goto sN1jkFPw;
	PdFMYRz4:d2WUifkJ:goto wPAyEbOg;
	xBmjKrbz:$CI->output->set_output($TdNT4MlS);
	goto HatLFgF_;
	mzYcDGEC:$ikv0xOvR = $CI->router->fetch_class();
	goto uBfc2r7m;
	R5b1PrlJ:$CI = &get_instance();
	goto mzYcDGEC;
	giTBQ9w2:goto d2WUifkJ;
	goto MTnbk9DY;
	DAqIlTAt:$CI->output->_display();
	goto giTBQ9w2;
	sN1jkFPw:$cUc6JqCL = array(
		"/\>[^\S ]+/s", "/[^\S ]+\</s",
		"/(\s)+/s",
		"#(?://)?<!\[CDATA\[(.*?)(?://)?\]\]>#s"
	);
	goto trdNiKjw;
	HatLFgF_:$CI->output->_display();
	goto PdFMYRz4;
	PnWdFfqv:
	if (!in_array($ikv0xOvR, $naQTsO5G)) {
		goto AIpSfLnZ;
	}
	goto DAqIlTAt;
	MTnbk9DY:AIpSfLnZ:goto LGB1wnl1;
	Tw6V4fYe:$TdNT4MlS = preg_replace($cUc6JqCL, $BU6MDXMh, $TdNT4MlS);
	goto xBmjKrbz;
	wPAyEbOg:
}