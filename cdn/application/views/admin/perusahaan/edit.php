<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

	<div id="wrapper">

		<div id="content-wrapper">

			<div class="container-fluid">

				<a href="<?php echo site_url('perusahaan') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
				<h4 class="page-title">Edit perusahaan</h4>

				<div class="row">
				<div class="col-md-6">
					<div class="card">
					<div class="card-body">


						<?php
	                    //create form
	                    $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
	                    echo form_open('', $attributes);
	                    ?>

	                    	<div class="form-group">
								<label for="nama_perusahaan">Nama</label>
								<input type="hidden" class="form-control" id="id_perusahaan" name="id_perusahaan" value=" <?= $data_perusahaan->id_perusahaan; ?>">
								<input class="form-control"	type="text" name="nama_perusahaan" id="nama_perusahaan" value=" <?= $data_perusahaan->nama_perusahaan; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('nama_perusahaan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="brand_perusahaan">Brand</label>
								<input class="form-control"	type="text" name="brand_perusahaan" id="brand_perusahaan" value=" <?= $data_perusahaan->brand_perusahaan; ?>" /
								>
								<small class="text-danger">
	                                <?php echo form_error('brand_perusahaan') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_hp">Nomor HP</label>
								<input class="form-control" type="text" name="no_hp" id="no_hp" value=" <?= $data_perusahaan->no_hp; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_hp') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input class="form-control" type="text" name="email" id="email" value=" <?= $data_perusahaan->email; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('email') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="alamat">Alamat</label>
								<input class="form-control" type="text" name="alamat" id="alamat" value=" <?= $data_perusahaan->alamat; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('alamat') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="kota">Kota</label>
								<input class="form-control" type="text" name="kota" id="kota" value=" <?= $data_perusahaan->kota; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('kota') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="provinsi">Provinsi</label>
								<input class="form-control" type="text" name="provinsi" id="provinsi" value=" <?= $data_perusahaan->provinsi; ?>" />
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
								<input class="form-control" type="text" name="nama_rekening" id="nama_rekening" value=" <?= $data_perusahaan->nama_rekening; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('nama_rekening') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="no_rekening">Rekening Bank</label>
								<input class="form-control" type="text" name="no_rekening" id="no_rekening" value=" <?= $data_perusahaan->no_rekening; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('no_rekening') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="website">Website</label>
								<input class="form-control" type="text" name="website" id="website" value=" <?= $data_perusahaan->website; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('website') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="npwp">NPWP</label>
								<input class="form-control" type="text" name="npwp" id="npwp" value=" <?= $data_perusahaan->npwp; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('npwp') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="logo">Logo Brand</label>
								<?php echo form_open_multipart('upload/do_upload');?>
								<input class="form" type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/jpg, image/gif" value="<?php echo set_value('logo'); ?>" style="width: 100%;" required />

					

								<small class="text-danger">
	                                <?php echo form_error('logo') ?>
	                            </small>
							</div>

							<div class="form-group">
								<label for="code">Qr Code</label>
								<input class="form-control" type="text" name="code" id="code" value=" <?= $data_perusahaan->code; ?>" />
								<small class="text-danger">
	                                <?php echo form_error('code') ?>
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