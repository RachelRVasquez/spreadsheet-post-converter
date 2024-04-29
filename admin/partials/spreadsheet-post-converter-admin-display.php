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
?>
<div class="wrap">
	<h1><?php _e('Spreadsheet to Post Type Converter', 'spreadsheet-post-converter');?></h1>
	<p class="description">
		<?php _e('Since this is a code sample of Rachel\'s, this expects an excel file with columns A - D, the first column is "Account Code", the rest are: Budget, Department, Year in that specific order.','spreadsheet-post-converter'); ?>
	</p>

	<form method="post" enctype="multipart/form-data" id="spc-upload-spreadsheet-form">
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th>
						<label for="spc-spreadsheet-upload">
							<?php _e( 'Select Spreadsheet', 'spreadsheet-post-converter' ); ?>
						</label>
					</th>
					<td>
						<input type="file" name="spc_spreadsheet_upload" id="spc-spreadsheet-upload"/>
						<p class="description"><?php _e( 'Only .xls and .xlsx accepted', 'spreadsheet-post-converter' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<button type="submit" name="spc-upload-spreadsheet" class="button button-primary">
			<?php _e( 'Upload File', 'spreadsheet-post-converter' ); ?>
		</button>
		<div class="spc-loading hidden">
			<?php _e( 'Processing... Thank you for your patience.', 'spreadsheet-post-converter' ); ?>
			<img src="<?php echo esc_url( SPREADSHEET_POST_CONVERTER_ASSETS_URL . '/spc-loading.gif' ); ?>" role="presentation"/>
		</div>
	</form>

	<!-- Div where we'll output the file contents to the front-end -->
	<div id="spc-excel-area"></div>
</div>