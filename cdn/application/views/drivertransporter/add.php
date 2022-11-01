<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

    <div id="wrapper">

        <div id="content-wrapper">

            <div class="container-fluid">

                <a href="<?php echo site_url('drivertransporter') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
                <h4 class="page-title">Tambah Driver</h4>

                <div class="card mb-3">
                    <div class="card-body">

                        <?php
                        //create form
                        $attributes = array('id' => 'FrmAddMahasiswa', 'method' => "post", "autocomplete" => "off");
                        echo form_open('', $attributes);
                        ?>

                        <div class="form-group">
                            <label for="nama driver">Nama Driver</label>
                            <input class="form-control" type="text" name="nama_driver" id="nama_driver" value=" <?= set_value('nama_driver'); ?>" />
                            <small class="text-danger">
                                <?php echo form_error('nama_driver') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="nama transporter">Nama Transporter</label>
                            <input class="form-control" type="text" name="nama_transporter" id="nama_transporter" value=" <?= set_value('nama_transporter'); ?>" />
                            <small class="text-danger">
                                <?php echo form_error('nama_transporter') ?>
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
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status" value=" <?= set_value('status'); ?>" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select> <br>
                            <small class="text-danger">
                                <?php echo form_error('status') ?>
                            </small>
                        </div>

                        <div class="card-body">
                            <button type="submit" class="btn btn-primary"><i class="la la-check-circle"></i> Simpan</button>
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