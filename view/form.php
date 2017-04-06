<!DOCTYPE html>
<html>
<head>
	<title>CRUD</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<!-- Font Awesome -->
	<link href='https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css' rel='stylesheet' type='text/css'>

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
	
	<script src="public/js/script.js"></script>

	<link rel="stylesheet" href="public/css/style.css">
</head>
<body>
	<div class="page-wrap">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h1 class="app-heading">PHP CRUD Application</h1>
				</div>
				<div class="col-md-6">
					<button class="btn btn-success float-right" type="button" data-toggle="modal" data-target="#add-new-user"><i class="fa fa-user-plus" aria-hidden="true"></i> Add User</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table table-hover table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Full Name</th>
								<th>Created On</th>
								<th>Status</th>
								<th colspan="2" class="align-right">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($crud->read()) : $read = true; ?>
							<?php foreach($crud->read() as $row) : ?>
							<tr>
								<td><?php echo $row['id']; ?></td>
								<td><?php echo $row['username']; ?></td>
								<td><?php echo $row['f_name'] . ' ' . $row['l_name']; ?></td>
								<td><?php echo $row['created']; ?></td>
								<td><?php echo $row['status']; ?></td>
								<td width="16%" class="align-right">
									<button class="btn btn-warning" data-toggle="modal" type="button" data-target="#edit-<?php echo $row['id'] ?>"><i class="fa fa-pencil-square-o"></i> Edit</button>
									<div id="edit-<?php echo $row['id']; ?>" class="modal fade row bs-example-modal-lg align-left" role="dialog">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<form class="form-edit-user" method="post">
													<input type="hidden" class="e-user-id" id="edit_user_id_<?php echo $row['id']; ?>" name="edit_user_id" value="<?php echo $row['id']; ?>" />

													<div class="loading hidden"><img src="img/loading.gif" alt=""></div>

													<!-- Start Modal Header -->
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title">Edit User</h4>
													</div>
													<!-- End Modal Header -->

													<!-- Start Modal Body -->
													<div class="modal-body">
														<div class="row">
															<div class="col-md-12">	
																<div class="alert alert-success" role="alert" style="display: none;"></div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">	
																<div id="first-name-group-<?php echo $row['id'] ?>" class="first-name-group form-group required">
																	<label for="first_name_<?php echo $row['id'] ?>">First name <span>*</span></label>
																	<input type="text" name="first_name" class="form-control" id="first_name_<?php echo $row['id'] ?>" placeholder="First Name" value="<?php echo $row['f_name']; ?>">
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">
																<div id="last-name-group-<?php echo $row['id'] ?>" class="last-name-group form-group required">
																	<label for="last_name_<?php echo $row['id'] ?>">Last name <span>*</span></label>
																	<input type="text" name="last_name" class="form-control" id="last_name_<?php echo $row['id'] ?>" placeholder="Last Name" value="<?php echo $row['l_name']; ?>">
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-md-12">
																<div id="status-group-<?php echo $row['id'] ?>" class="status-group form-group required">
																	<label for="status-<?php echo $row['id'] ?>">Status <span>*</span></label>
																	<select name="status" id="status-<?php echo $row['id'] ?>" class="form-control">
																		<option value="" disabled>Choose...</option>
																		<option value="1" <?php echo ($row['status'] == 'Active') ? "selected" : ""; ?>>Active</option>
																		<option value="2" <?php echo ($row['status'] == 'Inactive') ? "selected" : ""; ?>>Inactive</option>
																	</select>
																</div>
															</div>
														</div>
														
													</div> <!-- End Modal Body -->

													<!-- Start Modal Footer -->
													<div class="modal-footer">
														<button type="submit" class="btn btn-success" name="edit" value="edit"><i class="fa fa-pencil-square-o"></i> Edit User</button>
													</div>
													<!-- End Modal Footer -->
												</form>
											</div>
										</div>
									</div>
									<button class="delete-user btn btn-danger" type="button" data-delete="<?php echo $row['id']; ?>"><i class="fa fa-times-circle-o"></i> Delete
										<input type="hidden" class="d-user-id" name="delete-user" value="<?php echo $row['id']; ?>" />
									</button>
								</td>
							</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="6">Nothing found.</td>
							</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>

			<div id="add-new-user" class="modal fade row bs-example-modal-lg" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form id="form-add-user" method="post">
							
							<div class="loading hidden"><img src="img/loading.gif" alt=""></div>

							<!-- Start Modal Header -->
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">Add User</h4>
							</div>
							<!-- End Modal Header -->

							<!-- Start Modal Body -->
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">	
										<div class="alert alert-success" role="alert" style="display: none;"></div>
										<div id="username-group" class="form-group required">
											<label for="username">Username <span>*</span></label>
											<input type="text" name="username" class="form-control" id="username" placeholder="Username">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">	
										<div id="first-name-group" class="form-group required">
											<label for="first_name">First name <span>*</span></label>
											<input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div id="last-name-group" class="form-group required">
											<label for="last_name">Last name <span>*</span></label>
											<input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div id="status-group" class="form-group required">
											<label for="status">Status <span>*</span></label>
											<select name="status" id="status" class="form-control">
												<option value="">Choose...</option>
												<option value="1">Active</option>
												<option value="2">Inactive</option>
											</select>
										</div>
									</div>
								</div>
								
							</div> <!-- End Modal Body -->

							<!-- Start Modal Footer -->
							<div class="modal-footer">
								<button type="submit" class="btn btn-success" name="add" value="add"><i class="fa fa-user-plus"></i> Add User</button>
							</div>
							<!-- End Modal Footer -->
						</form>
					</div>
				</div>
			</div>
			<div class="modal fade" id="messageModal">
			    <div class="modal-dialog">
			        <div class="modal-content">
			        	<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title-success"></h4>
							<h4 class="modal-title-errors"></h4>
						</div>
			            <div class="modal-body">
			                <div id="errors"></div>
			                <div id="success"></div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	</div>
</body>
</html>