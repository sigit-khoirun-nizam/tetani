<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<a href="<?php echo site_url('karyawan') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
				<h4 class="page-title">Edit Karyawan</h4>

				<div class="card mb-3">
					<div class="card-body">

						<?php
	                    //create form
	                    $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

	                    	<div class="form-group">
								<label for="kode_karyawan">Kode Karyawan</label>
								<input type="hidden" class="form-control" id="id_karyawan" name="id_karyawan" value=" <?= $data_karyawan->id_karyawan; ?>">
								<input class="form-control"	type="text" name="kode_karyawan" id="kode_karyawan" value=" <?= $data_karyawan->kode_karyawan; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('kode_karyawan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_karyawan">Nama</label>
								<input class="form-control"	type="text" name="nama_karyawan" id="nama_karyawan" value=" <?= $data_karyawan->nama_karyawan; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('nama_karyawan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="nama_perusahaan">Perusahaan</label>
								<input class="form-control" type="text" name="nama_perusahaan" id="nama_perusahaan" value=" <?= $data_karyawan->nama_perusahaan; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('nama_perusahaan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="jabatan">Jabatan</label>
								<input class="form-control" type="text" name="jabatan" id="jabatan" value=" <?= $data_karyawan->jabatan; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('jabatan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_hp">No Hp</label>
								<input class="form-control" type="text" name="no_hp" id="no_hp" value=" <?= $data_karyawan->no_hp; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_hp') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input class="form-control" type="text" name="email" id="email" value=" <?= $data_karyawan->email; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('email') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="status">Status</label>
									<select class="form-control col-md-6" name="status" id="status" value=" <?= $data_karyawan->status; ?>" required >
										<option value="Aktif">Aktif</option>
										<option value="Tidak Aktif">Tidak Aktif</option>
									</select> <br>
								<small class="text-danger">
	                                <?php echo form_error('status') ?>
	                            </small>
							</div>

							<div class="card-body">
								<button type="submit" class="btn btn-primary"><i class="la la-check-circle"></i> Update</button>
							</div>
						</form>

					</div>

				</div>
				<!-- /.container-fluid -->

				<!-- Sticky Footer -->

			</div>
			<!-- /.content-wrapper -->

		</div>
		<!-- /#wrapper -->

</body>

</html>