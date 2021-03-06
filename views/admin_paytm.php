<div class="row content">
	<div class="col-md-12">
		<div class="row wrap-vertical">
			<ul id="nav-tabs" class="nav nav-tabs">
				<li class="active"><a href="#general" data-toggle="tab"><?php echo lang('text_tab_general'); ?></a></li>
			</ul>
		</div>

		<form role="form" id="edit-form" class="form-horizontal" accept-charset="utf-8" method="POST" action="<?php echo current_url(); ?>">
			<div class="tab-content">
				<div id="general" class="tab-pane row wrap-all active">
					<div class="form-group">
						<label for="input-title" class="col-sm-3 control-label"><?php echo lang('label_title'); ?></label>
						<div class="col-sm-5">
							<input type="text" name="title" id="input-title" class="form-control" value="<?php echo set_value('title', $title); ?>" />
							<?php echo form_error('title', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-merchant-id" class="col-sm-3 control-label"><?php echo lang('label_merchant_id'); ?></label>
						<div class="col-sm-5">
							<input type="text" name="merchant_id" id="input-merchant_id" class="form-control" value="<?php echo set_value('merchant_id', $merchant_id); ?>" />
							<?php echo form_error('merchant_id', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-merchant-key" class="col-sm-3 control-label"><?php echo lang('label_merchant_key'); ?></label>
						<div class="col-sm-5">
							<input type="text" name="merchant_key" id="input-merchant-key" class="form-control" value="<?php echo set_value('merchant_key', $merchant_key); ?>" />
							<?php echo form_error('merchant_key', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-website-url" class="col-sm-3 control-label"><?php echo lang('label_website_url'); ?></label>
						<div class="col-sm-5">
							<input type="text" name="website_url" id="input-website-url" class="form-control" value="<?php echo set_value('website_url', $website_url); ?>" />
							<?php echo form_error('website_url', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-channel-id" class="col-sm-3 control-label"><?php echo lang('label_channel_id'); ?></label>
						<div class="col-sm-5">
							<input type="text" name="channel_id" id="input-channel-id" class="form-control" value="<?php echo set_value('channel_id', $channel_id); ?>" />
							<?php echo form_error('channel_id', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-industry-type" class="col-sm-3 control-label"><?php echo lang('label_industry_type'); ?></label>
						<div class="col-sm-5">
							<input type="text" name="industry_type" id="input-industry-type" class="form-control" value="<?php echo set_value('industry_type', $industry_type); ?>" />
							<?php echo form_error('industry_type', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-transaction-mode" class="col-sm-3 control-label"><?php echo lang('label_transaction_mode'); ?></label>
						<div class="col-sm-5">
							<div class="btn-group btn-group-switch" data-toggle="buttons">
								<?php if ($transaction_mode === 'live') { ?>
									<label class="btn btn-warning"><input type="radio" name="transaction_mode" value="sandbox" <?php echo set_radio('transaction_mode', 'sandbox'); ?>><?php echo lang('text_sandbox'); ?></label>
									<label class="btn btn-success active"><input type="radio" name="transaction_mode" value="live" <?php echo set_radio('transaction_mode', 'live', TRUE); ?>><?php echo lang('text_go_live'); ?></label>
								<?php } else { ?>
									<label class="btn btn-warning active"><input type="radio" name="transaction_mode" value="sandbox" <?php echo set_radio('transaction_mode', 'sandbox', TRUE); ?>><?php echo lang('text_sandbox'); ?></label>
									<label class="btn btn-success"><input type="radio" name="transaction_mode" value="live" <?php echo set_radio('transaction_mode', 'live'); ?>><?php echo lang('text_go_live'); ?></label>
								<?php } ?>
							</div>
							<?php echo form_error('transaction_mode', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<!--  -->
					<div class="form-group">
						<label for="input-order-total" class="col-sm-3 control-label"><?php echo lang('label_order_total'); ?>
							<span class="help-block"><?php echo lang('help_order_total'); ?></span>
						</label>
						<div class="col-sm-5">
							<input type="text" name="order_total" id="input-order-total" class="form-control" value="<?php echo set_value('order_total', $order_total); ?>" />
							<?php echo form_error('order_total', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-order-status" class="col-sm-3 control-label"><?php echo lang('label_order_status'); ?>
							<span class="help-block"><?php echo lang('help_order_status'); ?></span>
						</label>
						<div class="col-sm-5">
							<select name="order_status" id="input-order-status" class="form-control">
								<?php foreach ($statuses as $stat) { ?>
								<?php if ($stat['status_id'] === $order_status) { ?>
									<option value="<?php echo $stat['status_id']; ?>" selected="selected"><?php echo $stat['status_name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $stat['status_id']; ?>"><?php echo $stat['status_name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
							<?php echo form_error('order_status', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-priority" class="col-sm-3 control-label"><?php echo lang('label_priority'); ?></label>
						<div class="col-sm-5">
							<input type="text" name="priority" id="input-priority" class="form-control" value="<?php echo $priority; ?>" />
							<?php echo form_error('priority', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="input-status" class="col-sm-3 control-label"><?php echo lang('label_status'); ?></label>
						<div class="col-sm-5">
							<div class="btn-group btn-group-switch" data-toggle="buttons">
								<?php if ($status == '1') { ?>
									<label class="btn btn-danger"><input type="radio" name="status" value="0" <?php echo set_radio('status', '0'); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success active"><input type="radio" name="status" value="1" <?php echo set_radio('status', '1', TRUE); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } else { ?>
									<label class="btn btn-danger active"><input type="radio" name="status" value="0" <?php echo set_radio('status', '0', TRUE); ?>><?php echo lang('text_disabled'); ?></label>
									<label class="btn btn-success"><input type="radio" name="status" value="1" <?php echo set_radio('status', '1'); ?>><?php echo lang('text_enabled'); ?></label>
								<?php } ?>
							</div>
							<?php echo form_error('status', '<span class="text-danger">', '</span>'); ?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>