<?php
$ac = array('','','','','','','','','','','','','');
if (!isset($pag)) { $pag = 0; }
$ac[$pag] = 'active';
?>
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo base_url('index.php/foaf'); ?>">
			<span class="glyphicon glyphicon-book" style="color: #aa3030;" aria-hidden="true"></span> <font color="#3030AA">foaf <sup>owl</sup></font></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="<?php echo $ac[0];?>">
					<a href="<?php echo base_url('index.php/foaf'); ?>"><?php echo msg('Home'); ?>
					<span class="sr-only">(current)</span></a>
				</li>
				<li class="<?php echo $ac[1];?>">
					<a href="<?php echo base_url('index.php/foaf/search'); ?>">Search</a>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>

<!--
INSERT INTO `rdf_resource` (`id_rs`, `rs_prefix`, `rs_propriety`, `rs_propriety_inverse`, `rs_type`, `rs_mandatory`, `rs_marc`, `rs_group`, `rs_public`) 
VALUES 
(NULL, '15', 'Agent', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'Document', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'Group', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'Image', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'LabelProperty', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'OnlineAccount', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'OnlineChatAccount', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'OnlineEcommerceAccount ', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'OnlineGamingAccount', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'Organization', '', 'C', '0', '', 'FOAF', '1'),
(NULL, '15', 'Person', '', 'C', '0', '', 'FOAF', '1'),
(NULL, '15', 'PersonalProfileDocument', '', 'C', '0', '', 'FOAF', '0'),
(NULL, '15', 'Project', '', 'C', '0', '', 'FOAF', '1');

account
accountName
accountServiceHomepage
age
aimChatID
based_near
birthday
currentProject
depiction
depicts
dnaChecksum
familyName
family_name
firstName
focus
fundedBy
geekcode
gender
givenName
givenname
holdsAccount
homepage
icqChatID
img
interest
isPrimaryTopicOf
jabberID
knows
lastName
logo
made
maker
mbox
mbox_sha1sum
member
membershipClass
msnChatID
myersBriggs
name
nick
openid
page
pastProject
phone
plan
primaryTopic
publications
schoolHomepage
sha1
skypeID
status
surname
theme
thumbnail
tipjar
title
topic
topic_interest
weblog
workInfoHomepage
workplaceHomepage
yahooChatID

http://erlangen-crm.org/docs/efrbroo/latest/
