<?php
	
     /*
     ###############################################
     ####                                       ####
     ####    Author : Harish Chauhan            ####
     ####    Date   : 31 Dec,2004               ####
     ####    Updated:                           ####
     ####                                       ####
     ###############################################

     */

	 
	 /*
	 * Class is used for save the data into microsoft excel format.
	 * It takes data into array or you can write data column vise.
	 */


	Class ExcelWriter
	{
			
		var $fp=null;
		var $error;
		var $state="CLOSED";
		var $newRow=false;
		
		/*
		* @Params : $file  : file name of excel file to be created.
		* @Return : On Success Valid File Pointer to file
		* 			On Failure return false	 
		*/
		 
		function ExcelWriter($file="")
		{
			return $this->open($file);
		}
		
		
		/* reza test */
		function test(){
			return "Reply From Excel Writer";	
		}
		
		
		/*
		* @Params : $file  : file name of excel file to be created.
		* 			if you are using file name with directory i.e. test/myFile.xls
		* 			then the directory must be existed on the system and have permissioned properly
		* 			to write the file.
		* @Return : On Success Valid File Pointer to file
		* 			On Failure return false	 
		*/
		function open($file)
		{
			if($this->state!="CLOSED")
			{
				$this->error="Error : Another file is opend .Close it to save the file";
				echo $this->error;
				return false;
			}	
			if(!empty($file))
			{
				$this->fp=@fopen($file,"w+");
			}
			else
			{
				$this->error="Usage : New ExcelWriter('fileName')";
				echo $this->error;
				return false;
			}	
			if($this->fp==false)
			{
				$this->error="Error: Unable to open/create File.You may not have permmsion to write the file.";
				echo $this->error;
				return false;
			}
			$this->state="OPENED";
			fwrite($this->fp,$this->GetHeader());
			return $this->fp;
		}
		
		function close()
		{
			if($this->state!="OPENED")
			{
				echo "ERR ".$this->state;
				$this->error="Error : Please open the file.";
				return false;
			}	
//				echo "close";
			
			if($this->newRow)
			{
				fwrite($this->fp,"</tr>");
				$this->newRow=false;
			}
			
			fwrite($this->fp,$this->GetFooter());
			fclose($this->fp);
			$this->state="CLOSED";
			
			return ;
		}
		/* @Params : Void
		*  @return : Void
		* This function write the header of Excel file.
		*/
		 							
		function GetHeader()
		{
			$header = <<<EOH
				<html xmlns:o="urn:schemas-microsoft-com:office:office"
				xmlns:x="urn:schemas-microsoft-com:office:excel"
				xmlns="http://www.w3.org/TR/REC-html40">

				<head>
				<meta http-equiv=Content-Type content="text/html; charset=us-ascii">
				<meta name=ProgId content=Excel.Sheet>
				<!--[if gte mso 9]><xml>
				 <o:DocumentProperties>
				  <o:LastAuthor>Sriram</o:LastAuthor>
				  <o:LastSaved>2005-01-02T07:46:23Z</o:LastSaved>
				  <o:Version>10.2625</o:Version>
				 </o:DocumentProperties>
				 <o:OfficeDocumentSettings>
				  <o:DownloadComponents/>
				 </o:OfficeDocumentSettings>
				</xml><![endif]-->
				<style>
				<!--table
					{mso-displayed-decimal-separator:"\.";
					mso-displayed-thousand-separator:"\,";}
				@page
					{margin:1.0in .75in 1.0in .75in;
					mso-header-margin:.5in;
					mso-footer-margin:.5in;}
				tr
					{mso-height-source:auto;}
				col
					{mso-width-source:auto;}
				br
					{mso-data-placement:same-cell;}
				.style0
					{mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					mso-rotate:0;
					mso-background-source:auto;
					mso-pattern:auto;
					color:windowtext;
					font-size:10.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					border:none;
					mso-protection:locked visible;
					mso-style-name:Normal;
					mso-style-id:0;}
				td
					{mso-style-parent:style0;
					padding-top:1px;
					padding-right:1px;
					padding-left:1px;
					mso-ignore:padding;
					color:windowtext;
					font-size:10.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Arial;
					mso-generic-font-family:auto;
					mso-font-charset:0;
					mso-number-format:General;
					text-align:general;
					vertical-align:bottom;
					border:none;
					mso-background-source:auto;
					mso-pattern:auto;
					mso-protection:locked visible;
					white-space:nowrap;
					mso-rotate:0;}
				.xl24
					{mso-style-parent:style0;
					white-space:normal;}
					

				.font6
					{color:black;
					font-size:8.0pt;
					font-weight:700;
					font-style:normal;
					text-decoration:none;
					font-family:Tahoma, sans-serif;}
				.font7
					{color:black;
					font-size:8.0pt;
					font-weight:700;
					font-style:normal;
					text-decoration:none;
					font-family:Tahoma, sans-serif;}
				.style0
					{text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					color:black;
					font-size:11.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Calibri, sans-serif;
					border:none;}
				.style58 {}
				.style59
					{text-align:general;
					vertical-align:bottom;
					white-space:nowrap;
					color:black;
					font-size:10.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Tahoma, sans-serif;
					border:none;}
				td
					{padding:0px;
					color:black;
					font-size:11.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Calibri, sans-serif;
					text-align:general;
					vertical-align:bottom;
					border:none;
					white-space:nowrap;}
				.xl66
					{vertical-align:middle;}
				.xl67
					{font-weight:700;
					vertical-align:middle;}
				.xl68
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:none;
					border-right:.5pt solid windowtext;
					border-bottom:.5pt solid windowtext;
					border-left:.5pt solid windowtext;}
				.xl69
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:.5pt solid windowtext;
					border-right:.5pt solid windowtext;
					border-bottom:none;
					border-left:.5pt solid windowtext;}
				.xl70
					{vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl71
					{font-size:12.0pt;
					font-family:"Arial Narrow", sans-serif;}
				.xl72
					{font-size:12.0pt;
					font-weight:700;
					font-family:"Arial Narrow", sans-serif;}
				.xl73
					{font-size:12.0pt;
					font-weight:700;
					font-family:"Arial Narrow", sans-serif;
					text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl74
					{color:windowtext;
					font-size:10.0pt;
					font-weight:700;
					font-family:Arial;
					text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl75
					{color:windowtext;
					font-size:10.0pt;
					font-weight:700;
					font-family:Arial;
					text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl76
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl77
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:none;
					border-right:none;
					border-bottom:.5pt solid windowtext;
					border-left:none;}
				.xl78
					{color:windowtext;
					font-size:12.0pt;
					font-weight:700;
					font-family:"Arial Narrow", sans-serif;
					text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl79
					{color:windowtext;
					font-size:12.0pt;
					font-weight:700;
					font-family:"Arial Narrow", sans-serif;
					text-align:left;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl80
					{color:windowtext;
					font-size:10.0pt;
					font-weight:700;
					font-family:Arial;
					text-align:center;
					vertical-align:middle;}
				.xl81
					{color:windowtext;
					font-size:10.0pt;
					font-weight:700;
					font-family:Arial;
					text-align:center;
					vertical-align:middle;}
				.xl82
					{font-weight:700;
					text-align:center;
					vertical-align:middle;}
				.xl83
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					white-space:normal;}
				.xl84
					{vertical-align:middle;
					border-top:none;
					border-right:.5pt solid windowtext;
					border-bottom:.5pt solid windowtext;
					border-left:.5pt solid windowtext;}
				.xl85
					{color:windowtext;
					font-size:10.0pt;
					font-weight:700;
					font-family:Arial;
					text-align:center;
					vertical-align:middle;
					border-top:none;
					border-right:none;
					border-bottom:.5pt solid windowtext;
					border-left:none;}
				.xl86
					{color:windowtext;
					font-size:10.0pt;
					font-weight:700;
					font-family:Arial;
					text-align:center;
					vertical-align:middle;
					border-top:none;
					border-right:none;
					border-bottom:.5pt solid windowtext;
					border-left:none;}
				.xl87
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:none;
					border-right:none;
					border-bottom:.5pt solid windowtext;
					border-left:none;
					white-space:normal;}
				.xl88
					{vertical-align:middle;
					white-space:normal;}
				.xl89
					{font-weight:700;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl90
					{font-size:12.0pt;
					font-weight:700;
					font-family:"Arial Narrow", sans-serif;
					text-align:center;
					vertical-align:middle;}
				.xl91
					{color:windowtext;
					font-size:12.0pt;
					font-weight:700;
					font-family:"Arial Narrow", sans-serif;
					text-align:left;
					vertical-align:middle;
					border-top:none;
					border-right:none;
					border-bottom:none;
					border-left:.5pt solid windowtext;}
				.xl92
					{font-size:12.0pt;
					font-weight:700;
					font-family:"Arial Narrow", sans-serif;
					text-align:left;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl93
					{color:windowtext;
					font-size:12.0pt;
					font-weight:700;
					font-family:"Arial Narrow", sans-serif;
					text-align:left;
					vertical-align:middle;}
				.xl94
					{font-size:10.0pt;
					font-family:Tahoma, sans-serif;
					text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl95
					{font-size:10.0pt;
					font-family:Tahoma, sans-serif;
					text-align:left;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl96
					{text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl97
					{vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl98
					{font-size:10.0pt;
					font-family:Tahoma, sans-serif;
					text-align:center;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl99
					{font-size:10.0pt;
					font-family:Tahoma, sans-serif;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl100
					{font-size:10.0pt;
					font-family:Tahoma, sans-serif;
					text-align:justify;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl101
					{font-size:10.0pt;
					font-family:Tahoma, sans-serif;
					vertical-align:middle;
					border:.5pt solid windowtext;}
				.xl102
					{font-weight:700;
					vertical-align:middle;
					border:.5pt solid windowtext;
					white-space:normal;}
				.xl103
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:.5pt solid windowtext;
					border-right:.5pt solid windowtext;
					border-bottom:none;
					border-left:.5pt solid windowtext;
					white-space:normal;}
				.xl104
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:none;
					border-right:.5pt solid windowtext;
					border-bottom:.5pt solid windowtext;
					border-left:.5pt solid windowtext;
					white-space:normal;}
				.xl105
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:.5pt solid windowtext;
					border-right:none;
					border-bottom:.5pt solid windowtext;
					border-left:.5pt solid windowtext;}
				.xl106
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:.5pt solid windowtext;
					border-right:.5pt solid windowtext;
					border-bottom:.5pt solid windowtext;
					border-left:none;}
				.xl107
					{font-size:12.0pt;
					font-weight:700;
					text-align:center;
					vertical-align:middle;}
				.xl108
					{font-weight:700;
					text-align:center;
					vertical-align:middle;
					border-top:none;
					border-right:.5pt solid windowtext;
					border-bottom:none;
					border-left:.5pt solid windowtext;}
				ruby
					{ruby-align:left;}
				rt
					{color:windowtext;
					font-size:8.0pt;
					font-weight:400;
					font-style:normal;
					text-decoration:none;
					font-family:Verdana;
					display:none;}
					
				-->
				</style>
				<!--[if gte mso 9]><xml>
				 <x:ExcelWorkbook>
				  <x:ExcelWorksheets>
				   <x:ExcelWorksheet>
					<x:Name>laporan</x:Name>
					<x:WorksheetOptions>
					 <x:Selected/>
					 <x:ProtectContents>False</x:ProtectContents>
					 <x:ProtectObjects>False</x:ProtectObjects>
					 <x:ProtectScenarios>False</x:ProtectScenarios>
					</x:WorksheetOptions>
				   </x:ExcelWorksheet>
				  </x:ExcelWorksheets>
				  <x:WindowHeight>10005</x:WindowHeight>
				  <x:WindowWidth>10005</x:WindowWidth>
				  <x:WindowTopX>120</x:WindowTopX>
				  <x:WindowTopY>135</x:WindowTopY>
				  <x:ProtectStructure>False</x:ProtectStructure>
				  <x:ProtectWindows>False</x:ProtectWindows>
				 </x:ExcelWorkbook>
				</xml><![endif]-->
				</head>

				<body link=blue vlink=purple>
				<table x:str border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse;table-layout:fixed;'>
EOH;
			return $header;
		}

		function GetFooter()
		{
			return "</table></body></html>";
		}
		
		/*
		* @Params : $line_arr: An valid array 
		* @Return : Void
		*/
		 
		function writeLine($line_arr)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if(!is_array($line_arr))
			{
				$this->error="Error : Argument is not valid. Supply an valid Array.";
				return false;
			}
			fwrite($this->fp,"<tr>");
			foreach($line_arr as $col)
				fwrite($this->fp,"<td class=xl24 width=64 >$col</td>");
			fwrite($this->fp,"</tr>");
		}
		
		/* adding Reza 3 des 2011, Write HTML */
		
		function writeHTML($html)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			fwrite($this->fp,$html);
		}		

		/*
		* @Params : Void
		* @Return : Void
		*/
		function writeRow()
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if($this->newRow==false)
				fwrite($this->fp,"<tr height=14 valign=top>");
			else
				fwrite($this->fp,"</tr><tr height=14 valign=top>");
			$this->newRow=true;	
		}


		function writeRowWithCss($style)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			if($this->newRow==false)
				fwrite($this->fp,"<tr height=14 valign=top class=$style>");
			else
				fwrite($this->fp,"</tr><tr height=14 valign=top class=$style>");
			$this->newRow=true;	
		}
		
		/*
		* @Params : $value : Coloumn Value
		* @Return : Void
		*/
		function writeCol($value)
		{
			if($this->state!="OPENED")
			{
				$this->error="Error : Please open the file.";
				return false;
			}	
			fwrite($this->fp,"<td class='xl65' width=64 valign=top >$value</td>");
		}
	}
?>