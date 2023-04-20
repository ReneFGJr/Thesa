<?php
$check = get("all");

$sa = '';
$sb = '';
$sx = bsc(form_open(PATH.'/search',['method'=>'get']),12);

$sa .= form_label(lang('thesa.term_search'),'label_search',['class'=>'small']);
$sa .= form_input('search',get("search"),['class'=> 'form-class full rounded big']);
$sx .= bsc($sa, 12);

$sb .= bsc(form_submit('action', lang('thesa.btn_search'), ['class' => 'mt-2 btn btn-outline-secondary']),6);
$sb .= bsc(form_checkbox('all',true,$check,['class'=>'mt-3']).lang('thesa.all_database'),6);
$sx .= $sb;

$sx .= bsc(form_close(),12);

echo bs($sx);
?>