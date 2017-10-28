<?php 
$searchpath = dirname(dirname(__FILE__)).'/data';
$outfilename = "";
$pdffiles ='kibana.pdf';
$page=empty($_GET['page'])?1:$_GET['page'];
try {
    $p = new \PDFlib();
    $p->set_option("errorpolicy=return");
    $p->set_option("stringformat=utf8");
    $p->set_option("SearchPath={{" . $searchpath . "}}");
    if ($p->begin_document($outfilename, "") == 0)
	die("Error: " . $p->get_errmsg());
    $p->set_info("Creator", "PDFlib starter sample");
    $p->set_info("Title", "starter_pdfmerge");
	$indoc = $p->open_pdi_document($pdffiles, "");
	if ($indoc == 0) {
	    printf("Error: %s\n", $p->get_errmsg());
	}
	$endpage = $p->pcos_get_number($indoc, "length:pages");
    if($page>$endpage){
        echo '页数过大';
        die;
    }
	    $page = $p->open_pdi_page($indoc,$page, "");
	    if ($page == 0) {
		printf("Error: %s\n", $p->get_errmsg());
	    }
	    $p->begin_page_ext(10, 10, "");
	    if (1 == 1) {
		$p->create_bookmark($pdffiles, "");
	    }
	    $p->fit_pdi_page($page, 0, 0, "adjustpage");
	    $p->close_pdi_page($page);
	    $p->end_page_ext("");
	$p->close_pdi_document($indoc);

    $p->end_document("");
    $buf = $p->get_buffer();
    $len = strlen($buf);
    header("Content-type: application/pdf");
    header("Content-Length: $len");
    header("Content-Disposition: inline; filename=starter_pdfmerge.pdf");
    print $buf;

}
catch (PDFlibException $e) {
    die("PDFlib exception occurred in starter_pdfmerge sample:\n" .
	"[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
	$e->get_errmsg() . "\n");
}
catch (Exception $e) {
    die($e);
}
?>