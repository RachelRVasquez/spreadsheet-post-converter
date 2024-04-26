<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://rachievee.com
 * @since      1.0.0
 *
 * @package    Spreadsheet_Post_Converter
 * @subpackage Spreadsheet_Post_Converter/admin/partials
 */

//Note to self: Do I need to keep the "action" on the form?
?>
<div class="wrap">
	<h1>Spreadsheet to Post Type Converter</h1>
	<p class="description">
		<?php _e('Since this is a code sample of Rachel\'s, this expects an excel file with columns A - D, the first column is "Account Code", the rest are: Budget, Department, Year in that specific order.','spreadsheet-post-converter'); ?>
	</p>

	<form method="post" enctype="multipart/form-data" id="sc-upload-spreadsheet-form" action="options.php">
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th>
					<label for="sc-spreadsheet-upload"><?php _e( 'Select Spreadsheet', 'spreadsheet-post-converter' ); ?></label>
					</th>
					<td>
						<input type="file" name="sc_spreadsheet_upload" id="sc-spreadsheet-upload"/>
						<p class="description"><?php _e( 'Only .xls and .xlsx accepted', 'spreadsheet-post-converter' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<button type="submit" name="sc-upload-spreadsheet" class="button button-primary">
			<?php _e( 'Upload File', 'spreadsheet-post-converter' ); ?>
		</button>
	</form>

	<!-- Div where we'll output the file contents to the front-end -->
	<div id="sc-excel-area"></div>
</div>