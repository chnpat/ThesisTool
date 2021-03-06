<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-check-square-o" aria-hidden="true"></i> Assessment
        <small>List</small>
      </h1>
    </section>
    <section class="content">
    	<div class="col-12">
			<?php if($this->session->flashdata("assess_msg")){ ?>
		    	<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Saved!</strong> <?php echo $this->session->flashdata("assess_msg"); ?>
				</div>
		    <?php } ?>
		    <?php if($this->session->flashdata("assess_error")){ ?>
		    	<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> <?php echo $this->session->flashdata("assess_error"); ?>
				</div>
		    <?php } ?>
		</div>
		<div class="box box-danger">
			<div class="box-header with-border">
				<span class="box-title"><b>Pending-for-Assessing Pattern List</b></span>
			</div>
	    	<div class="box-body table-responsive no-padding">
		    	<div class="panel-body">
		    		<table class="table table-bordered table-striped">
			    		<tr style="background-color: <?php echo TBL_GREY; ?>;">
			    			<th class="col-md-2 text-center">Pattern ID</th>
			    			<th class="col-md-6">Pattern Name</th>
			    			<th class="col-md-3 text-center">Pattern Version</th>
			    			<th class="col-md-1"></th>
			    		</tr>
			    		<?php foreach ($list as $pat) {
			    			echo "<tr>";
			    			echo "<td class='text-center'>".$pat['pattern_id']."</td>";
			    			echo "<td>".$pat['pattern_name']."</td>";
			    			echo "<td class='text-center'>".$pat['pattern_assess_version']."</td>";
			    			echo "<td>";
			    		?>
			    			<a href="<?php echo base_url(); ?>/cAssess/assess_choice/<?php echo $pat['pattern_id']; ?>/<?php echo $pat['pattern_assess_version']; ?>" class="btn bg-orange btn-xs"><i class="fa fa-check-square-o"></i> Assess</a>
			    		<?php
			    			echo "</td>";
			    			echo "</tr>";
			    		} ?>
			    	</table>
				</div>
	    	</div>
	    </div>
	    <div class="box box-danger">
	    	<div class="box-header with-border">
	    		<span class="box-title"><b>Assessed Pattern List</b></span>
	    	</div>
			<div class="box-body table-responsive">
	    		<table class="table table-bordered table-striped">
		    		<tr style="background-color: <?=TBL_GREY;?>;">
		    			<th class="col-md-2 col-xs-2 text-center">Pattern ID</th>
		    			<th class="col-md-6 col-xs-6">Pattern Name</th>
		    			<th class="col-md-3 col-xs-4 text-center">Pattern Version</th>
		    			<th class="col-md-1 hidden-xs hidden-sm"></th>
		    		</tr>
		    		<?php foreach ($done_list as $p) {
		    			echo "<tr>";
		    			echo "<td class='text-center'>".$p['pattern_id']."</td>";
		    			echo "<td>".$p['pattern_name']."</td>";
		    			echo "<td class='text-center'>".$p['pattern_assess_version']."</td>";
		    			echo "<td class='hidden-sm hidden-xs'>";
		    		?>
		    		<?php
		    			echo "</td>";
		    			echo "</tr>";
		    		} ?>
		    	</table>
			</div>
	    </div>
    </section>
</div>