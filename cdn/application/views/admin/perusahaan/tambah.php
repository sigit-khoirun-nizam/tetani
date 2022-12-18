<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<a href="<?php echo site_url('perusahaan') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
				<h4 class="page-title">Tambah Perusahaan</h4>

				<div class="row">
				<div class="col-md-6">
					<div class="card">
					<div class="card-body">

						<?php
	                    //create form
	                    $attributes = array('id_perusahaan' => 'FrmAddMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

	                    	<div class="form-group">
								<label for="nama_perusahaan">Nama</label>
								<input class="form-control"	type="text" name="nama_perusahaan" id="nama_perusahaan" value=" <?= set_value('nama_perusahaan'); ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('nama_perusahaan') ?>
	                            </small>
							</div>

							<div class="form-group ">
								<label for="brand_perusahaan">Brand</label>
								<input class="form-control"	type="text" name="brand_perusahaan" id="brand_perusahaan" value=" <?= set_value('brand_perusahaan'); ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('brand_perusahaan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_hp">Nomor HP</label>
								<input class="form-control" type="text" name="no_hp" id="no_hp" value=" <?= set_value('no_hp'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_hp') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input class="form-control" type="text" name="email" id="email" value=" <?= set_value('email'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('email') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="alamat">Alamat</label>
								<input class="form-control" type="text" name="alamat" id="alamat" value=" <?= set_value('alamat'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('alamat') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="kota">Kota</label>
								<input class="form-control" type="text" name="kota" id="kota" value=" <?= set_value('kota'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kota') ?>
	                            </small>
							</div>
						

							<div class="form-group">
								<label for="provinsi">Provinsi</label>
								<input class="form-control" type="text" name="provinsi" id="provinsi" value=" <?= set_value('provinsi'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('provinsi') ?>
	                            </small>
							</div>

							</div>
					</div>
				</div>

			<div class="row">
				<div class="col-md-12">
					<div class="card">
					<div class="card-body">

						<?php
	                    //create form
	                    $attributes = array('id_perusahaan' => 'FrmAddMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

							<div class="form-group">
								<label for="nama_rekening">Account Bank</label>
								<input class="form-control" type="text" name="nama_rekening" id="nama_rekening" value=" <?= set_value('nama_rekening'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('nama_rekening') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_rekening">Rekening Bank</label>
								<input class="form-control" type="text" name="no_rekening" id="no_rekening" value=" <?= set_value('no_rekening'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_rekening') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="Website">Website</label>
								<input class="form-control" type="text" name="website" id="website" value=" <?= set_value('website'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('website') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="npwp">NPWP</label>
								<input class="form-control" type="text" name="npwp" id="npwp" value=" <?= set_value('npwp'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('npwp') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="logo">Logo Brand</label>
								<?php echo form_open_multipart('upload/do_upload');?>
								<input class="form" type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/jpg, image/gif" value="<?php echo set_value('logo'); ?>" style="width: 100%;" required/>
								

								<small class="text-danger">
	                                <?php echo form_error('logo') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="code">Qr Code</label>
								<input class="form-control" type="text" name="code" id="code" value=" <?= set_value('code'); ?>" />
								<small class="text-danger">
	                                <?php echo form_error('code') ?>
	                            </small>
							</div>

							<div class="card-body">
								<button type="submit" class="btn btn-primary"><i class="la la-check-circle"></i> Simpan Kategori</button>
								<button type="reset" class="btn btn-warning"><i class="la la-refresh"></i> Reset</button>
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