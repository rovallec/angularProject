<?php
require 'funcionesVarias.php';

$company = $_GET['company'];
$joining_date = $_GET['hiring_date'];
$term_date = $_GET['term_date'];
$employee_name = $_GET['name'];
$employee_dpi = $_GET['dpi'];
$indemnizacion = $_GET['indemnizacion'];
$aguinaldo = $_GET['aguinaldo'];
$bono_14 = $_GET['bono_14'];
$vacaciones = $_GET['vacaciones'];
$acumulados = $_GET['acumulados'];
$salario_end = $_GET['end'];
$salario_start = $_GET['start'];
$isr = $_GET['isr'];
$descuentos = $_GET['debits'];
$bonos = $_GET['bonuses'];
$creditos = '0';
$total = (float)$indemnizacion + (float)$aguinaldo + (float)$bono_14 + (float)$vacaciones + (float)$acumulados + (float)$bonos - (float)$isr - (float)$descuentos;

$f = new NumberFormatter("es", NumberFormatter::SPELLOUT);

$term_day = explode("-", $term_date)[2];
$term_month = explode("-", $term_date)[1];
$term_year = explode("-", $term_date)[0];

$day_term_spell = $f->format($term_day);
$month_term_spell = getMonth($term_month);
$year_term_spell = $f->format($term_year);

$joining_day = explode("-", $joining_date)[2];
$joining_month = explode("-", $joining_date)[1];
$joining_year = explode("-", $joining_date)[0];

$day_joining_spell = $f->format($joining_day);
$month_joining_spell = getMonth($joining_month);
$year_joining_spell = $f->format($joining_year);


echo "
<html xmlns:v='urn:schemas-microsoft-com:vml'
xmlns:o='urn:schemas-microsoft-com:office:office'
xmlns:w='urn:schemas-microsoft-com:office:word'
xmlns:m='http://schemas.microsoft.com/office/2004/12/omml'
xmlns='http://www.w3.org/TR/REC-html40'>

<head>
<meta http-equiv=Content-Type content='text/html; charset=windows-1252'>
<meta name=ProgId content=Word.Document>
<meta name=Generator content='Microsoft Word 15'>
<meta name=Originator content='Microsoft Word 15'>
<link rel=File-List href='finiquito%20laboral_archivos/filelist.xml'>
<link rel=Edit-Time-Data href='finiquito%20laboral_archivos/editdata.mso'>
<!--[if !mso]>
<style>
v\:* {behavior:url(#default#VML);}
o\:* {behavior:url(#default#VML);}
w\:* {behavior:url(#default#VML);}
.shape {behavior:url(#default#VML);}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>Oscar Garcia</o:Author>
  <o:LastAuthor>Raul Alejandro Ovalle Castillo</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>11</o:TotalTime>
  <o:LastPrinted>2016-11-04T12:42:00Z</o:LastPrinted>
  <o:Created>2021-03-22T17:00:00Z</o:Created>
  <o:LastSaved>2021-03-22T17:00:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Words>304</o:Words>
  <o:Characters>1736</o:Characters>
  <o:Lines>14</o:Lines>
  <o:Paragraphs>4</o:Paragraphs>
  <o:CharactersWithSpaces>2036</o:CharactersWithSpaces>
  <o:Version>16.00</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:AllowPNG/>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<link rel=dataStoreItem href='finiquito%20laboral_archivos/item0001.xml'
target='finiquito%20laboral_archivos/props002.xml'>
<link rel=themeData href='finiquito%20laboral_archivos/themedata.thmx'>
<link rel=colorSchemeMapping
href='finiquito%20laboral_archivos/colorschememapping.xml'>
<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:SpellingState>Clean</w:SpellingState>
  <w:GrammarState>Clean</w:GrammarState>
  <w:TrackMoves>false</w:TrackMoves>
  <w:TrackFormatting/>
  <w:HyphenationZone>21</w:HyphenationZone>
  <w:PunctuationKerning/>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:DoNotPromoteQF/>
  <w:LidThemeOther>EN-US</w:LidThemeOther>
  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
  <w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
   <w:DontGrowAutofit/>
   <w:SplitPgBreakAndParaMark/>
   <w:EnableOpenTypeKerning/>
   <w:DontFlipMirrorIndents/>
   <w:OverrideTableStyleHps/>
  </w:Compatibility>
  <m:mathPr>
   <m:mathFont m:val='Cambria Math'/>
   <m:brkBin m:val='before'/>
   <m:brkBinSub m:val='&#45;-'/>
   <m:smallFrac m:val='off'/>
   <m:dispDef/>
   <m:lMargin m:val='0'/>
   <m:rMargin m:val='0'/>
   <m:defJc m:val='centerGroup'/>
   <m:wrapIndent m:val='1440'/>
   <m:intLim m:val='subSup'/>
   <m:naryLim m:val='undOvr'/>
  </m:mathPr></w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState='false' DefUnhideWhenUsed='false'
  DefSemiHidden='false' DefQFormat='false' DefPriority='99'
  LatentStyleCount='376'>
  <w:LsdException Locked='false' Priority='0' QFormat='true' Name='Normal'/>
  <w:LsdException Locked='false' Priority='9' QFormat='true' Name='heading 1'/>
  <w:LsdException Locked='false' Priority='9' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='heading 2'/>
  <w:LsdException Locked='false' Priority='9' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='heading 3'/>
  <w:LsdException Locked='false' Priority='9' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='heading 4'/>
  <w:LsdException Locked='false' Priority='9' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='heading 5'/>
  <w:LsdException Locked='false' Priority='9' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='heading 6'/>
  <w:LsdException Locked='false' Priority='9' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='heading 7'/>
  <w:LsdException Locked='false' Priority='9' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='heading 8'/>
  <w:LsdException Locked='false' Priority='9' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='heading 9'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 5'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 6'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 7'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 8'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index 9'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 1'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 2'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 3'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 4'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 5'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 6'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 7'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 8'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' Name='toc 9'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Normal Indent'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='footnote text'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='annotation text'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='header'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='footer'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='index heading'/>
  <w:LsdException Locked='false' Priority='35' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='caption'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='table of figures'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='envelope address'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='envelope return'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='footnote reference'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='annotation reference'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='line number'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='page number'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='endnote reference'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='endnote text'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='table of authorities'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='macro'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='toa heading'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Bullet'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Number'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List 5'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Bullet 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Bullet 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Bullet 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Bullet 5'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Number 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Number 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Number 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Number 5'/>
  <w:LsdException Locked='false' Priority='10' QFormat='true' Name='Title'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Closing'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Signature'/>
  <w:LsdException Locked='false' Priority='1' SemiHidden='true'
   UnhideWhenUsed='true' Name='Default Paragraph Font'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Body Text'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Body Text Indent'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Continue'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Continue 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Continue 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Continue 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='List Continue 5'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Message Header'/>
  <w:LsdException Locked='false' Priority='11' QFormat='true' Name='Subtitle'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Salutation'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Date'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Body Text First Indent'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Body Text First Indent 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Note Heading'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Body Text 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Body Text 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Body Text Indent 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Body Text Indent 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Block Text'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Hyperlink'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='FollowedHyperlink'/>
  <w:LsdException Locked='false' Priority='22' QFormat='true' Name='Strong'/>
  <w:LsdException Locked='false' Priority='20' QFormat='true' Name='Emphasis'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Document Map'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Plain Text'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='E-mail Signature'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Top of Form'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Bottom of Form'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Normal (Web)'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Acronym'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Address'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Cite'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Code'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Definition'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Keyboard'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Preformatted'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Sample'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Typewriter'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='HTML Variable'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Normal Table'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='annotation subject'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='No List'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Outline List 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Outline List 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Outline List 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Simple 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Simple 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Simple 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Classic 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Classic 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Classic 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Classic 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Colorful 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Colorful 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Colorful 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Columns 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Columns 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Columns 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Columns 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Columns 5'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Grid 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Grid 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Grid 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Grid 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Grid 5'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Grid 6'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Grid 7'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Grid 8'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table List 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table List 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table List 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table List 4'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table List 5'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table List 6'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table List 7'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table List 8'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table 3D effects 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table 3D effects 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table 3D effects 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Contemporary'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Elegant'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Professional'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Subtle 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Subtle 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Web 1'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Web 2'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Web 3'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Balloon Text'/>
  <w:LsdException Locked='false' Priority='39' Name='Table Grid'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Table Theme'/>
  <w:LsdException Locked='false' SemiHidden='true' Name='Placeholder Text'/>
  <w:LsdException Locked='false' Priority='1' QFormat='true' Name='No Spacing'/>
  <w:LsdException Locked='false' Priority='60' Name='Light Shading'/>
  <w:LsdException Locked='false' Priority='61' Name='Light List'/>
  <w:LsdException Locked='false' Priority='62' Name='Light Grid'/>
  <w:LsdException Locked='false' Priority='63' Name='Medium Shading 1'/>
  <w:LsdException Locked='false' Priority='64' Name='Medium Shading 2'/>
  <w:LsdException Locked='false' Priority='65' Name='Medium List 1'/>
  <w:LsdException Locked='false' Priority='66' Name='Medium List 2'/>
  <w:LsdException Locked='false' Priority='67' Name='Medium Grid 1'/>
  <w:LsdException Locked='false' Priority='68' Name='Medium Grid 2'/>
  <w:LsdException Locked='false' Priority='69' Name='Medium Grid 3'/>
  <w:LsdException Locked='false' Priority='70' Name='Dark List'/>
  <w:LsdException Locked='false' Priority='71' Name='Colorful Shading'/>
  <w:LsdException Locked='false' Priority='72' Name='Colorful List'/>
  <w:LsdException Locked='false' Priority='73' Name='Colorful Grid'/>
  <w:LsdException Locked='false' Priority='60' Name='Light Shading Accent 1'/>
  <w:LsdException Locked='false' Priority='61' Name='Light List Accent 1'/>
  <w:LsdException Locked='false' Priority='62' Name='Light Grid Accent 1'/>
  <w:LsdException Locked='false' Priority='63' Name='Medium Shading 1 Accent 1'/>
  <w:LsdException Locked='false' Priority='64' Name='Medium Shading 2 Accent 1'/>
  <w:LsdException Locked='false' Priority='65' Name='Medium List 1 Accent 1'/>
  <w:LsdException Locked='false' SemiHidden='true' Name='Revision'/>
  <w:LsdException Locked='false' Priority='34' QFormat='true'
   Name='List Paragraph'/>
  <w:LsdException Locked='false' Priority='29' QFormat='true' Name='Quote'/>
  <w:LsdException Locked='false' Priority='30' QFormat='true'
   Name='Intense Quote'/>
  <w:LsdException Locked='false' Priority='66' Name='Medium List 2 Accent 1'/>
  <w:LsdException Locked='false' Priority='67' Name='Medium Grid 1 Accent 1'/>
  <w:LsdException Locked='false' Priority='68' Name='Medium Grid 2 Accent 1'/>
  <w:LsdException Locked='false' Priority='69' Name='Medium Grid 3 Accent 1'/>
  <w:LsdException Locked='false' Priority='70' Name='Dark List Accent 1'/>
  <w:LsdException Locked='false' Priority='71' Name='Colorful Shading Accent 1'/>
  <w:LsdException Locked='false' Priority='72' Name='Colorful List Accent 1'/>
  <w:LsdException Locked='false' Priority='73' Name='Colorful Grid Accent 1'/>
  <w:LsdException Locked='false' Priority='60' Name='Light Shading Accent 2'/>
  <w:LsdException Locked='false' Priority='61' Name='Light List Accent 2'/>
  <w:LsdException Locked='false' Priority='62' Name='Light Grid Accent 2'/>
  <w:LsdException Locked='false' Priority='63' Name='Medium Shading 1 Accent 2'/>
  <w:LsdException Locked='false' Priority='64' Name='Medium Shading 2 Accent 2'/>
  <w:LsdException Locked='false' Priority='65' Name='Medium List 1 Accent 2'/>
  <w:LsdException Locked='false' Priority='66' Name='Medium List 2 Accent 2'/>
  <w:LsdException Locked='false' Priority='67' Name='Medium Grid 1 Accent 2'/>
  <w:LsdException Locked='false' Priority='68' Name='Medium Grid 2 Accent 2'/>
  <w:LsdException Locked='false' Priority='69' Name='Medium Grid 3 Accent 2'/>
  <w:LsdException Locked='false' Priority='70' Name='Dark List Accent 2'/>
  <w:LsdException Locked='false' Priority='71' Name='Colorful Shading Accent 2'/>
  <w:LsdException Locked='false' Priority='72' Name='Colorful List Accent 2'/>
  <w:LsdException Locked='false' Priority='73' Name='Colorful Grid Accent 2'/>
  <w:LsdException Locked='false' Priority='60' Name='Light Shading Accent 3'/>
  <w:LsdException Locked='false' Priority='61' Name='Light List Accent 3'/>
  <w:LsdException Locked='false' Priority='62' Name='Light Grid Accent 3'/>
  <w:LsdException Locked='false' Priority='63' Name='Medium Shading 1 Accent 3'/>
  <w:LsdException Locked='false' Priority='64' Name='Medium Shading 2 Accent 3'/>
  <w:LsdException Locked='false' Priority='65' Name='Medium List 1 Accent 3'/>
  <w:LsdException Locked='false' Priority='66' Name='Medium List 2 Accent 3'/>
  <w:LsdException Locked='false' Priority='67' Name='Medium Grid 1 Accent 3'/>
  <w:LsdException Locked='false' Priority='68' Name='Medium Grid 2 Accent 3'/>
  <w:LsdException Locked='false' Priority='69' Name='Medium Grid 3 Accent 3'/>
  <w:LsdException Locked='false' Priority='70' Name='Dark List Accent 3'/>
  <w:LsdException Locked='false' Priority='71' Name='Colorful Shading Accent 3'/>
  <w:LsdException Locked='false' Priority='72' Name='Colorful List Accent 3'/>
  <w:LsdException Locked='false' Priority='73' Name='Colorful Grid Accent 3'/>
  <w:LsdException Locked='false' Priority='60' Name='Light Shading Accent 4'/>
  <w:LsdException Locked='false' Priority='61' Name='Light List Accent 4'/>
  <w:LsdException Locked='false' Priority='62' Name='Light Grid Accent 4'/>
  <w:LsdException Locked='false' Priority='63' Name='Medium Shading 1 Accent 4'/>
  <w:LsdException Locked='false' Priority='64' Name='Medium Shading 2 Accent 4'/>
  <w:LsdException Locked='false' Priority='65' Name='Medium List 1 Accent 4'/>
  <w:LsdException Locked='false' Priority='66' Name='Medium List 2 Accent 4'/>
  <w:LsdException Locked='false' Priority='67' Name='Medium Grid 1 Accent 4'/>
  <w:LsdException Locked='false' Priority='68' Name='Medium Grid 2 Accent 4'/>
  <w:LsdException Locked='false' Priority='69' Name='Medium Grid 3 Accent 4'/>
  <w:LsdException Locked='false' Priority='70' Name='Dark List Accent 4'/>
  <w:LsdException Locked='false' Priority='71' Name='Colorful Shading Accent 4'/>
  <w:LsdException Locked='false' Priority='72' Name='Colorful List Accent 4'/>
  <w:LsdException Locked='false' Priority='73' Name='Colorful Grid Accent 4'/>
  <w:LsdException Locked='false' Priority='60' Name='Light Shading Accent 5'/>
  <w:LsdException Locked='false' Priority='61' Name='Light List Accent 5'/>
  <w:LsdException Locked='false' Priority='62' Name='Light Grid Accent 5'/>
  <w:LsdException Locked='false' Priority='63' Name='Medium Shading 1 Accent 5'/>
  <w:LsdException Locked='false' Priority='64' Name='Medium Shading 2 Accent 5'/>
  <w:LsdException Locked='false' Priority='65' Name='Medium List 1 Accent 5'/>
  <w:LsdException Locked='false' Priority='66' Name='Medium List 2 Accent 5'/>
  <w:LsdException Locked='false' Priority='67' Name='Medium Grid 1 Accent 5'/>
  <w:LsdException Locked='false' Priority='68' Name='Medium Grid 2 Accent 5'/>
  <w:LsdException Locked='false' Priority='69' Name='Medium Grid 3 Accent 5'/>
  <w:LsdException Locked='false' Priority='70' Name='Dark List Accent 5'/>
  <w:LsdException Locked='false' Priority='71' Name='Colorful Shading Accent 5'/>
  <w:LsdException Locked='false' Priority='72' Name='Colorful List Accent 5'/>
  <w:LsdException Locked='false' Priority='73' Name='Colorful Grid Accent 5'/>
  <w:LsdException Locked='false' Priority='60' Name='Light Shading Accent 6'/>
  <w:LsdException Locked='false' Priority='61' Name='Light List Accent 6'/>
  <w:LsdException Locked='false' Priority='62' Name='Light Grid Accent 6'/>
  <w:LsdException Locked='false' Priority='63' Name='Medium Shading 1 Accent 6'/>
  <w:LsdException Locked='false' Priority='64' Name='Medium Shading 2 Accent 6'/>
  <w:LsdException Locked='false' Priority='65' Name='Medium List 1 Accent 6'/>
  <w:LsdException Locked='false' Priority='66' Name='Medium List 2 Accent 6'/>
  <w:LsdException Locked='false' Priority='67' Name='Medium Grid 1 Accent 6'/>
  <w:LsdException Locked='false' Priority='68' Name='Medium Grid 2 Accent 6'/>
  <w:LsdException Locked='false' Priority='69' Name='Medium Grid 3 Accent 6'/>
  <w:LsdException Locked='false' Priority='70' Name='Dark List Accent 6'/>
  <w:LsdException Locked='false' Priority='71' Name='Colorful Shading Accent 6'/>
  <w:LsdException Locked='false' Priority='72' Name='Colorful List Accent 6'/>
  <w:LsdException Locked='false' Priority='73' Name='Colorful Grid Accent 6'/>
  <w:LsdException Locked='false' Priority='19' QFormat='true'
   Name='Subtle Emphasis'/>
  <w:LsdException Locked='false' Priority='21' QFormat='true'
   Name='Intense Emphasis'/>
  <w:LsdException Locked='false' Priority='31' QFormat='true'
   Name='Subtle Reference'/>
  <w:LsdException Locked='false' Priority='32' QFormat='true'
   Name='Intense Reference'/>
  <w:LsdException Locked='false' Priority='33' QFormat='true' Name='Book Title'/>
  <w:LsdException Locked='false' Priority='37' SemiHidden='true'
   UnhideWhenUsed='true' Name='Bibliography'/>
  <w:LsdException Locked='false' Priority='39' SemiHidden='true'
   UnhideWhenUsed='true' QFormat='true' Name='TOC Heading'/>
  <w:LsdException Locked='false' Priority='41' Name='Plain Table 1'/>
  <w:LsdException Locked='false' Priority='42' Name='Plain Table 2'/>
  <w:LsdException Locked='false' Priority='43' Name='Plain Table 3'/>
  <w:LsdException Locked='false' Priority='44' Name='Plain Table 4'/>
  <w:LsdException Locked='false' Priority='45' Name='Plain Table 5'/>
  <w:LsdException Locked='false' Priority='40' Name='Grid Table Light'/>
  <w:LsdException Locked='false' Priority='46' Name='Grid Table 1 Light'/>
  <w:LsdException Locked='false' Priority='47' Name='Grid Table 2'/>
  <w:LsdException Locked='false' Priority='48' Name='Grid Table 3'/>
  <w:LsdException Locked='false' Priority='49' Name='Grid Table 4'/>
  <w:LsdException Locked='false' Priority='50' Name='Grid Table 5 Dark'/>
  <w:LsdException Locked='false' Priority='51' Name='Grid Table 6 Colorful'/>
  <w:LsdException Locked='false' Priority='52' Name='Grid Table 7 Colorful'/>
  <w:LsdException Locked='false' Priority='46'
   Name='Grid Table 1 Light Accent 1'/>
  <w:LsdException Locked='false' Priority='47' Name='Grid Table 2 Accent 1'/>
  <w:LsdException Locked='false' Priority='48' Name='Grid Table 3 Accent 1'/>
  <w:LsdException Locked='false' Priority='49' Name='Grid Table 4 Accent 1'/>
  <w:LsdException Locked='false' Priority='50' Name='Grid Table 5 Dark Accent 1'/>
  <w:LsdException Locked='false' Priority='51'
   Name='Grid Table 6 Colorful Accent 1'/>
  <w:LsdException Locked='false' Priority='52'
   Name='Grid Table 7 Colorful Accent 1'/>
  <w:LsdException Locked='false' Priority='46'
   Name='Grid Table 1 Light Accent 2'/>
  <w:LsdException Locked='false' Priority='47' Name='Grid Table 2 Accent 2'/>
  <w:LsdException Locked='false' Priority='48' Name='Grid Table 3 Accent 2'/>
  <w:LsdException Locked='false' Priority='49' Name='Grid Table 4 Accent 2'/>
  <w:LsdException Locked='false' Priority='50' Name='Grid Table 5 Dark Accent 2'/>
  <w:LsdException Locked='false' Priority='51'
   Name='Grid Table 6 Colorful Accent 2'/>
  <w:LsdException Locked='false' Priority='52'
   Name='Grid Table 7 Colorful Accent 2'/>
  <w:LsdException Locked='false' Priority='46'
   Name='Grid Table 1 Light Accent 3'/>
  <w:LsdException Locked='false' Priority='47' Name='Grid Table 2 Accent 3'/>
  <w:LsdException Locked='false' Priority='48' Name='Grid Table 3 Accent 3'/>
  <w:LsdException Locked='false' Priority='49' Name='Grid Table 4 Accent 3'/>
  <w:LsdException Locked='false' Priority='50' Name='Grid Table 5 Dark Accent 3'/>
  <w:LsdException Locked='false' Priority='51'
   Name='Grid Table 6 Colorful Accent 3'/>
  <w:LsdException Locked='false' Priority='52'
   Name='Grid Table 7 Colorful Accent 3'/>
  <w:LsdException Locked='false' Priority='46'
   Name='Grid Table 1 Light Accent 4'/>
  <w:LsdException Locked='false' Priority='47' Name='Grid Table 2 Accent 4'/>
  <w:LsdException Locked='false' Priority='48' Name='Grid Table 3 Accent 4'/>
  <w:LsdException Locked='false' Priority='49' Name='Grid Table 4 Accent 4'/>
  <w:LsdException Locked='false' Priority='50' Name='Grid Table 5 Dark Accent 4'/>
  <w:LsdException Locked='false' Priority='51'
   Name='Grid Table 6 Colorful Accent 4'/>
  <w:LsdException Locked='false' Priority='52'
   Name='Grid Table 7 Colorful Accent 4'/>
  <w:LsdException Locked='false' Priority='46'
   Name='Grid Table 1 Light Accent 5'/>
  <w:LsdException Locked='false' Priority='47' Name='Grid Table 2 Accent 5'/>
  <w:LsdException Locked='false' Priority='48' Name='Grid Table 3 Accent 5'/>
  <w:LsdException Locked='false' Priority='49' Name='Grid Table 4 Accent 5'/>
  <w:LsdException Locked='false' Priority='50' Name='Grid Table 5 Dark Accent 5'/>
  <w:LsdException Locked='false' Priority='51'
   Name='Grid Table 6 Colorful Accent 5'/>
  <w:LsdException Locked='false' Priority='52'
   Name='Grid Table 7 Colorful Accent 5'/>
  <w:LsdException Locked='false' Priority='46'
   Name='Grid Table 1 Light Accent 6'/>
  <w:LsdException Locked='false' Priority='47' Name='Grid Table 2 Accent 6'/>
  <w:LsdException Locked='false' Priority='48' Name='Grid Table 3 Accent 6'/>
  <w:LsdException Locked='false' Priority='49' Name='Grid Table 4 Accent 6'/>
  <w:LsdException Locked='false' Priority='50' Name='Grid Table 5 Dark Accent 6'/>
  <w:LsdException Locked='false' Priority='51'
   Name='Grid Table 6 Colorful Accent 6'/>
  <w:LsdException Locked='false' Priority='52'
   Name='Grid Table 7 Colorful Accent 6'/>
  <w:LsdException Locked='false' Priority='46' Name='List Table 1 Light'/>
  <w:LsdException Locked='false' Priority='47' Name='List Table 2'/>
  <w:LsdException Locked='false' Priority='48' Name='List Table 3'/>
  <w:LsdException Locked='false' Priority='49' Name='List Table 4'/>
  <w:LsdException Locked='false' Priority='50' Name='List Table 5 Dark'/>
  <w:LsdException Locked='false' Priority='51' Name='List Table 6 Colorful'/>
  <w:LsdException Locked='false' Priority='52' Name='List Table 7 Colorful'/>
  <w:LsdException Locked='false' Priority='46'
   Name='List Table 1 Light Accent 1'/>
  <w:LsdException Locked='false' Priority='47' Name='List Table 2 Accent 1'/>
  <w:LsdException Locked='false' Priority='48' Name='List Table 3 Accent 1'/>
  <w:LsdException Locked='false' Priority='49' Name='List Table 4 Accent 1'/>
  <w:LsdException Locked='false' Priority='50' Name='List Table 5 Dark Accent 1'/>
  <w:LsdException Locked='false' Priority='51'
   Name='List Table 6 Colorful Accent 1'/>
  <w:LsdException Locked='false' Priority='52'
   Name='List Table 7 Colorful Accent 1'/>
  <w:LsdException Locked='false' Priority='46'
   Name='List Table 1 Light Accent 2'/>
  <w:LsdException Locked='false' Priority='47' Name='List Table 2 Accent 2'/>
  <w:LsdException Locked='false' Priority='48' Name='List Table 3 Accent 2'/>
  <w:LsdException Locked='false' Priority='49' Name='List Table 4 Accent 2'/>
  <w:LsdException Locked='false' Priority='50' Name='List Table 5 Dark Accent 2'/>
  <w:LsdException Locked='false' Priority='51'
   Name='List Table 6 Colorful Accent 2'/>
  <w:LsdException Locked='false' Priority='52'
   Name='List Table 7 Colorful Accent 2'/>
  <w:LsdException Locked='false' Priority='46'
   Name='List Table 1 Light Accent 3'/>
  <w:LsdException Locked='false' Priority='47' Name='List Table 2 Accent 3'/>
  <w:LsdException Locked='false' Priority='48' Name='List Table 3 Accent 3'/>
  <w:LsdException Locked='false' Priority='49' Name='List Table 4 Accent 3'/>
  <w:LsdException Locked='false' Priority='50' Name='List Table 5 Dark Accent 3'/>
  <w:LsdException Locked='false' Priority='51'
   Name='List Table 6 Colorful Accent 3'/>
  <w:LsdException Locked='false' Priority='52'
   Name='List Table 7 Colorful Accent 3'/>
  <w:LsdException Locked='false' Priority='46'
   Name='List Table 1 Light Accent 4'/>
  <w:LsdException Locked='false' Priority='47' Name='List Table 2 Accent 4'/>
  <w:LsdException Locked='false' Priority='48' Name='List Table 3 Accent 4'/>
  <w:LsdException Locked='false' Priority='49' Name='List Table 4 Accent 4'/>
  <w:LsdException Locked='false' Priority='50' Name='List Table 5 Dark Accent 4'/>
  <w:LsdException Locked='false' Priority='51'
   Name='List Table 6 Colorful Accent 4'/>
  <w:LsdException Locked='false' Priority='52'
   Name='List Table 7 Colorful Accent 4'/>
  <w:LsdException Locked='false' Priority='46'
   Name='List Table 1 Light Accent 5'/>
  <w:LsdException Locked='false' Priority='47' Name='List Table 2 Accent 5'/>
  <w:LsdException Locked='false' Priority='48' Name='List Table 3 Accent 5'/>
  <w:LsdException Locked='false' Priority='49' Name='List Table 4 Accent 5'/>
  <w:LsdException Locked='false' Priority='50' Name='List Table 5 Dark Accent 5'/>
  <w:LsdException Locked='false' Priority='51'
   Name='List Table 6 Colorful Accent 5'/>
  <w:LsdException Locked='false' Priority='52'
   Name='List Table 7 Colorful Accent 5'/>
  <w:LsdException Locked='false' Priority='46'
   Name='List Table 1 Light Accent 6'/>
  <w:LsdException Locked='false' Priority='47' Name='List Table 2 Accent 6'/>
  <w:LsdException Locked='false' Priority='48' Name='List Table 3 Accent 6'/>
  <w:LsdException Locked='false' Priority='49' Name='List Table 4 Accent 6'/>
  <w:LsdException Locked='false' Priority='50' Name='List Table 5 Dark Accent 6'/>
  <w:LsdException Locked='false' Priority='51'
   Name='List Table 6 Colorful Accent 6'/>
  <w:LsdException Locked='false' Priority='52'
   Name='List Table 7 Colorful Accent 6'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Mention'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Smart Hyperlink'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Hashtag'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Unresolved Mention'/>
  <w:LsdException Locked='false' SemiHidden='true' UnhideWhenUsed='true'
   Name='Smart Link'/>
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:'Cambria Math';
	panose-1:2 4 5 3 5 4 6 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:3 0 0 0 1 0;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-469750017 -1073732485 9 0 511 0;}
@font-face
	{font-family:'Segoe UI';
	panose-1:2 11 5 2 4 2 4 2 2 3;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-469750017 -1073683329 9 0 511 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:'';
	margin-top:0in;
	margin-right:0in;
	margin-bottom:8.0pt;
	margin-left:0in;
	line-height:107%;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;}
p.MsoCommentText, li.MsoCommentText, div.MsoCommentText
	{mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-link:'Texto comentario Car';
	margin-top:0in;
	margin-right:0in;
	margin-bottom:8.0pt;
	margin-left:0in;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{mso-style-priority:99;
	mso-style-link:'Encabezado Car';
	margin:0in;
	mso-pagination:widow-orphan;
	tab-stops:center 3.25in right 6.5in;
	font-size:11.0pt;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{mso-style-priority:99;
	mso-style-link:'Pie de página Car';
	margin:0in;
	mso-pagination:widow-orphan;
	tab-stops:center 3.25in right 6.5in;
	font-size:11.0pt;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;}
span.MsoCommentReference
	{mso-style-noshow:yes;
	mso-style-priority:99;
	mso-ansi-font-size:8.0pt;
	mso-bidi-font-size:8.0pt;}
p.MsoCommentSubject, li.MsoCommentSubject, div.MsoCommentSubject
	{mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-parent:'Texto comentario';
	mso-style-link:'Asunto del comentario Car';
	mso-style-next:'Texto comentario';
	margin-top:0in;
	margin-right:0in;
	margin-bottom:8.0pt;
	margin-left:0in;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;
	font-weight:bold;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-link:'Texto de globo Car';
	margin:0in;
	mso-pagination:widow-orphan;
	font-size:9.0pt;
	font-family:'Segoe UI',sans-serif;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;}
p.MsoNoSpacing, li.MsoNoSpacing, div.MsoNoSpacing
	{mso-style-priority:1;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:'';
	margin:0in;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;}
span.EncabezadoCar
	{mso-style-name:'Encabezado Car';
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:Encabezado;}
span.PiedepginaCar
	{mso-style-name:'Pie de página Car';
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:'Pie de página';}
span.TextodegloboCar
	{mso-style-name:'Texto de globo Car';
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:'Texto de globo';
	mso-ansi-font-size:9.0pt;
	mso-bidi-font-size:9.0pt;
	font-family:'Segoe UI',sans-serif;
	mso-ascii-font-family:'Segoe UI';
	mso-hansi-font-family:'Segoe UI';
	mso-bidi-font-family:'Segoe UI';}
span.TextocomentarioCar
	{mso-style-name:'Texto comentario Car';
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:'Texto comentario';
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;}
span.AsuntodelcomentarioCar
	{mso-style-name:'Asunto del comentario Car';
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-parent:'Texto comentario Car';
	mso-style-link:'Asunto del comentario';
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;
	font-weight:bold;}
span.GramE
	{mso-style-name:'';
	mso-gram-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;}
.MsoPapDefault
	{mso-style-type:export-only;
	margin-bottom:8.0pt;
	line-height:107%;}
 /* Page Definitions */
 @page
	{mso-footnote-separator:url('finiquito%20laboral_archivos/header.htm') fs;
	mso-footnote-continuation-separator:url('finiquito%20laboral_archivos/header.htm') fcs;
	mso-endnote-separator:url('finiquito%20laboral_archivos/header.htm') es;
	mso-endnote-continuation-separator:url('finiquito%20laboral_archivos/header.htm') ecs;}
@page WordSection1
	{size:8.5in 11.0in;
	margin:.75in 1.0in 1.0in 1.0in;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-page-numbers:1;
	mso-header:url('finiquito%20laboral_archivos/header.htm') h1;
	mso-footer:url('finiquito%20laboral_archivos/header.htm') f1;
	mso-paper-source:0;}
div.WordSection1
	{page:WordSection1;}
@page WordSection2
	{size:8.5in 11.0in;
	margin:.75in 1.0in 1.0in 1.0in;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-even-header:url('finiquito%20laboral_archivos/header.htm') eh2;
	mso-header:url('finiquito%20laboral_archivos/header.htm') h2;
	mso-even-footer:url('finiquito%20laboral_archivos/header.htm') ef2;
	mso-footer:url('finiquito%20laboral_archivos/header.htm') f2;
	mso-first-header:url('finiquito%20laboral_archivos/header.htm') fh2;
	mso-first-footer:url('finiquito%20laboral_archivos/header.htm') ff2;
	mso-paper-source:0;}
div.WordSection2
	{page:WordSection2;}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:'Tabla normal';
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-parent:'';
	mso-padding-alt:0in 5.4pt 0in 5.4pt;
	mso-para-margin-top:0in;
	mso-para-margin-right:0in;
	mso-para-margin-bottom:8.0pt;
	mso-para-margin-left:0in;
	line-height:107%;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;}
table.MsoTableGrid
	{mso-style-name:'Tabla con cuadrícula';
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-priority:39;
	mso-style-unhide:no;
	border:solid windowtext 1.0pt;
	mso-border-alt:solid windowtext .5pt;
	mso-padding-alt:0in 5.4pt 0in 5.4pt;
	mso-border-insideh:.5pt solid windowtext;
	mso-border-insidev:.5pt solid windowtext;
	mso-para-margin:0in;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:'Calibri',sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:'Times New Roman';
	mso-bidi-theme-font:minor-bidi;}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:shapedefaults v:ext='edit' spidmax='2051'/>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <o:shapelayout v:ext='edit'>
  <o:idmap v:ext='edit' data='1'/>
 </o:shapelayout></xml><![endif]-->
</head>

<body lang=EN-US style='tab-interval:.5in;word-wrap:break-word'>

<div class=WordSection1>

<p class=MsoNormal align=center style='text-align:center;text-indent:.5in'><span
lang=ES-GT style='font-size:36.0pt;line-height:107%;mso-ansi-language:ES-GT'>$company
<o:p></o:p></span></p>

<p class=MsoNormal align=center style='text-align:center;text-indent:.5in'><span
lang=ES-GT style='font-size:24.0pt;line-height:107%;mso-ansi-language:ES-GT'>FINIQUITO
LABORAL<o:p></o:p></span></p>

<p class=MsoNormal style='text-align:justify'><span lang=ES-GT
style='mso-ansi-language:ES-GT'>Por este medio HAGO CONSTAR que labore para la
empresa $company desde el <span style='mso-no-proof:yes'>$day_joining_spell<span
style='mso-spacerun:yes'>  </span>de $month_joining_spell de $year_joining_spell</span>
hasta el <span style='mso-no-proof:yes'>$day_term_spell de $month_term_spell de $year_term_spell</span>
<span class=GramE>como<span style='mso-spacerun:yes'>  </span><b
style='mso-bidi-font-weight:normal'><span style='mso-no-proof:yes'>CUSTOMER</span></b></span><b
style='mso-bidi-font-weight:normal'><span style='mso-no-proof:yes'> SERVICE
AGENT</span></b><span style='mso-spacerun:yes'>  </span>y que por motivo de<span
style='mso-spacerun:yes'>  </span><span style='mso-no-proof:yes'>Despido</span><span
style='mso-spacerun:yes'>  </span>al cargo, hago constar por este medio que
recibo a mi entera satisfacción el pago de las prestaciones laborales
legalmente establecidas según se describen a continuación:<o:p></o:p></span></p>

<div align=center>

<table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0 width=500
 style='width:375.35pt;border-collapse:collapse;border:none;mso-yfti-tbllook:
 1184;mso-padding-alt:0in 5.4pt 0in 5.4pt;mso-border-insideh:none;mso-border-insidev:
 none'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:12.65pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><b style='mso-bidi-font-weight:normal'><span
  lang=ES-GT style='mso-ansi-language:ES-GT'>Descripción</span></b><span
  lang=ES-GT style='mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><b style='mso-bidi-font-weight:normal'><span
  lang=ES-GT style='mso-ansi-language:ES-GT'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><b style='mso-bidi-font-weight:normal'><span
  lang=ES-GT style='mso-ansi-language:ES-GT'>Monto<o:p></o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1;height:12.65pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Indemnización<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-bidi-theme-font:minor-bidi;mso-ansi-language:ES;mso-no-proof:yes'><span
  style='mso-spacerun:yes'>     </span>$indemnizacion</span><span lang=ES-GT
  style='mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:2;height:12.65pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Aguinaldo
  Proporcional<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-bidi-theme-font:minor-bidi;mso-ansi-language:ES;mso-no-proof:yes'><span
  style='mso-spacerun:yes'>  </span>$aguinaldo</span><span lang=ES-GT
  style='mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:3;height:12.65pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Bono
  14 Proporcional<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-bidi-theme-font:minor-bidi;mso-ansi-language:ES;mso-no-proof:yes'><span
  style='mso-spacerun:yes'>   </span>$bono_14</span><span lang=ES-GT
  style='mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:4;height:12.65pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Vacaciones
  Proporcionales<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-bidi-theme-font:minor-bidi;mso-ansi-language:ES;mso-no-proof:yes'><span
  style='mso-spacerun:yes'>   </span>$vacaciones</span><span lang=ES-GT
  style='mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:5;height:11.9pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:11.9pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Sueldos
  del<span style='mso-spacerun:yes'> $salario_start </span>al $salario_end<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:11.9pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:11.9pt'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-bidi-theme-font:minor-bidi;mso-ansi-language:ES;mso-no-proof:yes'><span
  style='mso-spacerun:yes'>     </span>$acumulados</span><span lang=ES-GT
  style='mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:6;height:12.65pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Bonificación
  (Decretos 78-89, 7-2000 y 37-2001)<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-bidi-theme-font:minor-bidi;mso-ansi-language:ES;mso-no-proof:yes'>$bonos<o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:7;height:21.75pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:21.75pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Devolución
  o Retención ISR<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:21.75pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:21.75pt;border-bottom:1.5px black solid'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES-GT
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-ansi-language:ES-GT;mso-no-proof:yes'>$isr</span><span lang=ES-GT
  style='font-family:'Times New Roman',serif;mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:8;height:12.65pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing align=right style='text-align:right'><!--[if gte vml 1]><v:line
   id='Straight_x0020_Connector_x0020_5' o:spid='_x0000_s1027' style='position:absolute;
   left:0;text-align:left;z-index:251659264;visibility:visible;
   mso-wrap-style:square;mso-width-percent:0;mso-height-percent:0;
   mso-wrap-distance-left:9pt;mso-wrap-distance-top:0;
   mso-wrap-distance-right:9pt;mso-wrap-distance-bottom:0;
   mso-position-horizontal:absolute;mso-position-horizontal-relative:text;
   mso-position-vertical:absolute;mso-position-vertical-relative:text;
   mso-width-percent:0;mso-height-percent:0;mso-width-relative:margin;
   mso-height-relative:margin' from='-13.1pt,-.35pt' to='95.65pt,-.35pt'
   o:gfxdata='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
' strokecolor='black [3213]' strokeweight='.5pt'>
   <v:stroke joinstyle='miter'/>
  </v:line><![endif]--><![if !vml]><span style='mso-ignore:vglayout;position:
  relative;z-index:251659264'></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:9;mso-yfti-lastrow:yes;height:12.65pt'>
  <td width=337 valign=top style='width:253.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Compensación<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.25pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=141 valign=top style='width:106.05pt;padding:0in 5.4pt 0in 5.4pt;
  height:12.65pt'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-bidi-theme-font:minor-bidi;mso-ansi-language:ES;mso-no-proof:yes'><span
  style='mso-spacerun:yes'>  </span>$creditos</span><span lang=ES-GT
  style='mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
 </tr>
</table>

</div>

<p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'><o:p>&nbsp;</o:p></span></p>

<div align=center>

<table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0 width=507
 style='width:380.5pt;border-collapse:collapse;border:none;mso-yfti-tbllook:
 1184;mso-padding-alt:0in 5.4pt 0in 5.4pt;mso-border-insideh:none;mso-border-insidev:
 none'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;
  height:.2in'>
  <td width=342 valign=top style='width:256.55pt;padding:0in 5.4pt 0in 5.4pt;
  height:.2in'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>(-)<span
  style='mso-spacerun:yes'>  </span>Descuentos<o:p></o:p></span></p>
  </td>
  <td width=22 valign=top style='width:16.4pt;padding:0in 5.4pt 0in 5.4pt;
  height:.2in'>
  <p class=MsoNoSpacing><span lang=ES-GT style='mso-ansi-language:ES-GT'>Q<o:p></o:p></span></p>
  </td>
  <td width=143 valign=top style='width:107.55pt;padding:0in 5.4pt 0in 5.4pt;
  height:.2in;border-bottom:1.5px black solid'>
  <p class=MsoNoSpacing align=right style='text-align:right'><span lang=ES
  style='font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:'Times New Roman',serif;
  mso-bidi-theme-font:minor-bidi;mso-ansi-language:ES;mso-no-proof:yes'><span
  style='mso-spacerun:yes'>     </span>$descuentos</span><span lang=ES-GT
  style='mso-ansi-language:ES-GT'><o:p></o:p></span></p>
  </td>
 </tr>
</table>

</div>

<p class=MsoNormal><!--[if gte vml 1]><v:line id='Straight_x0020_Connector_x0020_6'
 o:spid='_x0000_s1026' style='position:absolute;flip:y;z-index:251660288;
 visibility:visible;mso-wrap-style:square;mso-width-percent:0;
 mso-height-percent:0;mso-wrap-distance-left:9pt;mso-wrap-distance-top:0;
 mso-wrap-distance-right:9pt;mso-wrap-distance-bottom:0;
 mso-position-horizontal:absolute;mso-position-horizontal-relative:text;
 mso-position-vertical:absolute;mso-position-vertical-relative:text;
 mso-width-percent:0;mso-height-percent:0;mso-width-relative:margin;
 mso-height-relative:margin' from='303.75pt,.95pt' to='421.5pt,1.7pt'
 o:gfxdata='UEsDBBQABgAIAAAAIQC2gziS/gAAAOEBAAATAAAAW0NvbnRlbnRfVHlwZXNdLnhtbJSRQU7DMBBF
90jcwfIWJU67QAgl6YK0S0CoHGBkTxKLZGx5TGhvj5O2G0SRWNoz/78nu9wcxkFMGNg6quQqL6RA
0s5Y6ir5vt9lD1JwBDIwOMJKHpHlpr69KfdHjyxSmriSfYz+USnWPY7AufNIadK6MEJMx9ApD/oD
OlTrorhX2lFEilmcO2RdNtjC5xDF9pCuTyYBB5bi6bQ4syoJ3g9WQ0ymaiLzg5KdCXlKLjvcW893
SUOqXwnz5DrgnHtJTxOsQfEKIT7DmDSUCaxw7Rqn8787ZsmRM9e2VmPeBN4uqYvTtW7jvijg9N/y
JsXecLq0q+WD6m8AAAD//wMAUEsDBBQABgAIAAAAIQA4/SH/1gAAAJQBAAALAAAAX3JlbHMvLnJl
bHOkkMFqwzAMhu+DvYPRfXGawxijTi+j0GvpHsDYimMaW0Yy2fr2M4PBMnrbUb/Q94l/f/hMi1qR
JVI2sOt6UJgd+ZiDgffL8ekFlFSbvV0oo4EbChzGx4f9GRdb25HMsYhqlCwG5lrLq9biZkxWOiqY
22YiTra2kYMu1l1tQD30/bPm3wwYN0x18gb45AdQl1tp5j/sFB2T0FQ7R0nTNEV3j6o9feQzro1i
OWA14Fm+Q8a1a8+Bvu/d/dMb2JY5uiPbhG/ktn4cqGU/er3pcvwCAAD//wMAUEsDBBQABgAIAAAA
IQBnKNJ62QEAABAEAAAOAAAAZHJzL2Uyb0RvYy54bWysU01vEzEQvSPxHyzfyW6iJqKrbHpIVS4I
Ikq5u95x1pK/NDbZ5N8z9iabCpAQVS+WP+a9mfdmvL47WsMOgFF71/L5rOYMnPSddvuWP31/+PCR
s5iE64TxDlp+gsjvNu/frYfQwML33nSAjEhcbIbQ8j6l0FRVlD1YEWc+gKNH5dGKREfcVx2Kgdit
qRZ1vaoGj11ALyFGur0fH/mm8CsFMn1VKkJipuVUWyorlvU5r9VmLZo9itBreS5DvKIKK7SjpBPV
vUiC/UT9B5XVEn30Ks2kt5VXSksoGkjNvP5NzWMvAhQtZE4Mk03x7Wjll8MOme5avuLMCUstekwo
9L5PbOudIwM9slX2aQixofCt2+H5FMMOs+ijQsuU0eEHjUCxgYSxY3H5NLkMx8QkXc5vbpc3iyVn
kt5ul7QjumpkyWwBY/oE3rK8abnRLnsgGnH4HNMYegnJ18blNXqjuwdtTDnk6YGtQXYQ1Pd0nJ9T
vIiihBlZZVWjjrJLJwMj6zdQ5Euut2QvE3nlFFKCSxde4yg6wxRVMAHrfwPP8RkKZVr/BzwhSmbv
0gS22nn8W/arFWqMvzgw6s4WPPvuVDpcrKGxK805f5E81y/PBX79yJtfAAAA//8DAFBLAwQUAAYA
CAAAACEA+PQ09N4AAAAHAQAADwAAAGRycy9kb3ducmV2LnhtbEyPwU7DMBBE70j8g7VI3KhTWtoS
4lQIiQNSVUrbA9xce0kC8TrYThv+nuUEx9UbzbwtloNrxRFDbDwpGI8yEEjG24YqBfvd49UCREya
rG49oYJvjLAsz88KnVt/ohc8blMluIRirhXUKXW5lNHU6HQc+Q6J2bsPTic+QyVt0Ccud628zrKZ
dLohXqh1hw81ms9t7xS8jp++Nqb72OyezeotrNJ6jalX6vJiuL8DkXBIf2H41Wd1KNnp4HuyUbQK
Ztn8hqMMbkEwX0wn/NtBwWQKsizkf//yBwAA//8DAFBLAQItABQABgAIAAAAIQC2gziS/gAAAOEB
AAATAAAAAAAAAAAAAAAAAAAAAABbQ29udGVudF9UeXBlc10ueG1sUEsBAi0AFAAGAAgAAAAhADj9
If/WAAAAlAEAAAsAAAAAAAAAAAAAAAAALwEAAF9yZWxzLy5yZWxzUEsBAi0AFAAGAAgAAAAhAGco
0nrZAQAAEAQAAA4AAAAAAAAAAAAAAAAALgIAAGRycy9lMm9Eb2MueG1sUEsBAi0AFAAGAAgAAAAh
APj0NPTeAAAABwEAAA8AAAAAAAAAAAAAAAAAMwQAAGRycy9kb3ducmV2LnhtbFBLBQYAAAAABAAE
APMAAAA+BQAAAAA=
' strokecolor='black [3213]' strokeweight='.5pt'>
 <v:stroke joinstyle='miter'/>
</v:line><![endif]--><![if !vml]><![endif]><span
lang=ES-GT style='mso-ansi-language:ES-GT'><span style='mso-tab-count:2'>                                </span></span><span
lang=ES-GT style='font-size:5.0pt;line-height:107%;mso-ansi-language:ES-GT'><o:p></o:p></span></p>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span lang=ES-GT
style='font-size:14.0pt;line-height:107%;mso-ansi-language:ES-GT'><span
style='mso-spacerun:yes'>                      </span>Total a Pagar<span
style='mso-tab-count:4'>                                                </span><span
style='mso-spacerun:yes'>       </span>
<span style='border-bottom:2px solid black'>
<span>
    Q
    <span style='mso-spacerun:yes'>     
    </span><span style='mso-spacerun:yes'>       </span>
    <span style='mso-spacerun:yes'> </span>
</span>
<span lang=ES style='font-size:14.0pt;line-height:107%;font-family:Times New Roman,serif;mso-bidi-theme-font:minor-bidi; mso-ansi-language:ES; mso-no-proof:yes;'>
<span style='mso-spacerun:yes'>  </span>$total</span>
</b><b style='mso-bidi-font-weight:normal'>
<span lang=ES-GT style='font-size:14.0pt; line-height:107%;mso-ansi-language:ES-GT'><o:p></o:p></span>
</b>
</span>
</p>

<p class=MsoNormal style='text-align:justify'><span lang=ES-GT
style='mso-ansi-language:ES-GT'>También hago constar que durante<span
style='mso-spacerun:yes'>  </span>el tiempo de la relación laboral recibí el
pago completo de mis días,<span style='mso-spacerun:yes'>  </span>hasta el
momento que se da por terminada la relación laboral. <span
style='mso-spacerun:yes'>  </span>En virtud de lo anterior, otorgó a la empresa
$company, el más AMPLIO, TOTAL Y EFICAZ FINIQUITO derivado de la
relación de mis servicios, el día <span style='mso-no-proof:yes'>tres de Noviembre
de dos mil veinte</span> haciendo constar de manera expresa y sin presión
alguna que desisto de cualquier acción administrativa o judicial por esta causa<span
style='mso-spacerun:yes'>  </span>y a desestimar cualquier acción iniciada al
respecto, entiendo los alcances legales de esta declaración.<o:p></o:p></span></p>

<p class=MsoNormal><span lang=ES-GT style='mso-ansi-language:ES-GT'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal><span lang=ES-GT style='mso-ansi-language:ES-GT'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNoSpacing align=center style='text-align:center'><b
style='mso-bidi-font-weight:normal'><span lang=ES-GT style='mso-ansi-language:
ES-GT;mso-no-proof:yes'>$employee_name</span></b><b
style='mso-bidi-font-weight:normal'><span lang=ES-GT style='mso-ansi-language:
ES-GT'><o:p></o:p></span></b></p>

<p class=MsoNormal align=center style='text-align:center'><b style='mso-bidi-font-weight:
normal'><span lang=ES-GT style='mso-ansi-language:ES-GT'><span
style='mso-spacerun:yes'> </span>DPI <span style='mso-no-proof:yes'>$employee_dpi</span></span></b><span
lang=ES-GT style='color:red;mso-ansi-language:ES-GT'><o:p></o:p></span></p>

<p class=MsoNormal style='text-align:justify'><span lang=ES-GT
style='mso-ansi-language:ES-GT'>En la ciudad de Guatemala, a los <span
style='mso-no-proof:yes'>tres dias del mes de Noviembre de dos mil veinte</span>
como <b style='mso-bidi-font-weight:normal'>NOTARIO, DOY FE</b>; que la firma
que antecede es auténtica por haber sido signada en mi presencia el día de hoy <span
class=GramE>por,<span style='mso-spacerun:yes'>   </span></span><span
style='mso-no-proof:yes'>$employee_name</span><span
style='mso-spacerun:yes'>  </span>quien se identifica con número de DPI <span
style='mso-no-proof:yes'>$employee_dpi</span><span style='mso-spacerun:yes'> 
</span>extendido en el departamento de Guatemala quien vuelve a firmar la
presente acta de legalización de firmas, juntamente con el notario autorizante.<o:p></o:p></span></p>

<p class=MsoNormal><span lang=ES-GT style='mso-ansi-language:ES-GT'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNoSpacing><b style='mso-bidi-font-weight:normal'><span lang=ES-GT
style='mso-ansi-language:ES-GT;mso-no-proof:yes'>$employee_name</span></b><span lang=ES-GT style='mso-ansi-language:ES-GT'><span
style='mso-tab-count:4'>                                                              </span><b
style='mso-bidi-font-weight:normal'>FIRMA Y SELLO DEL NOTARIO</b><o:p></o:p></span></p>

<p class=MsoNoSpacing><b style='mso-bidi-font-weight:normal'><span lang=ES-GT
style='mso-ansi-language:ES-GT'>NO. DE DPI<span style='mso-spacerun:yes'> 
</span><span style='mso-no-proof:yes'>$employee_dpi</span><o:p></o:p></span></b></p>

</div>

<b style='mso-bidi-font-weight:normal'><span lang=ES-GT style='font-size:11.0pt;
font-family:'Calibri',sans-serif;mso-ascii-theme-font:minor-latin;mso-fareast-font-family:
Calibri;mso-fareast-theme-font:minor-latin;mso-hansi-theme-font:minor-latin;
mso-bidi-font-family:'Times New Roman';mso-bidi-theme-font:minor-bidi;
mso-ansi-language:ES-GT;mso-fareast-language:EN-US;mso-bidi-language:AR-SA'><br
clear=all style='page-break-before:auto;mso-break-type:section-break'>
</span></b>

<div class=WordSection2>

<p class=MsoNoSpacing><b style='mso-bidi-font-weight:normal'><span lang=ES-GT
style='mso-ansi-language:ES-GT'><o:p>&nbsp;</o:p></span></b></p>

</div>
<footer align=center style='border-top:8px double #b56e3e;position:absolute;bottom:0;width:100%;height:5rem'>
  <p style='text-align:center;text-indent:.5in'>20 Calle 5-25, Zona 10, Guatemala<br>
    Tel. (502) 2504-2757
  </p>
</footer>

</body>

</html>
"
?>