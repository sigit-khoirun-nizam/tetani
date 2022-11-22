<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body id="page-top">

    <div id="wrapper">

        <div id="content-wrapper">

            <div class="container-fluid">

                <a href="<?php echo site_url('datatransporter') ?>" class="btn btn-danger float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
                <h4 class="page-title">Edit Petani</h4>

                <div class="card mb-3">
                    <div class="card-body">

                        <?php
                        //create form
                        $attributes = array('id' => 'FrmEditMahasiswa', 'method' => "post", "autocomplete" => "off");
                        echo form_open('', $attributes);
                        ?>

                        <div class="form-group">
                            <label for="nama driver">Nama Driver</label>
                            <input type="hidden" class="form-control" id="id" name="id" value=" <?= $driver_transporter->id; ?>">
                            <input type="text" class="form-control" id="nama_driver" name="nama_driver" value=" <?= $driver_transporter->nama_driver; ?>">
                            <small class="text-danger">
                                <?php echo form_error('nama_driver') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="nama transporter">Nama Transporter</label>
                            <input type="text" class="form-control" id="nama_transporter" name="nama_transporter" value=" <?= $driver_transporter->nama_transporter; ?>">
                            <small class="text-danger">
                                <?php echo form_error('nama_transporter') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="no hp">No Hp</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value=" <?= $driver_transporter->no_hp; ?>">
                            <small class="text-danger">
                                <?php echo form_error('no_hp') ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="Pilih" selected disabled>Pilih</option>
                                <option value="Aktif" <?php if ($driver_transporter->status == "Aktif") : echo "selected";
                                                        endif; ?>>Aktif</option>
                                <option value="Tidak Aktif" <?php if ($driver_transporter->status == "Tidak Aktif") : echo "selected";
                                                            endif; ?>>Tidak Aktif</option>
                            </select>
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