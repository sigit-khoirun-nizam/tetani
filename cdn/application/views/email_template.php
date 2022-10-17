<div style="background:#f2f2f2;font-family:arial,sans-serif;">
  <div style="width:90%;max-width:600px;margin:auto;padding:20px 0 50px 0;box-sizing:border-box;">
    <div style="background:#1abc9c;padding:12px;text-align:center;width:100%;border-radius:8px 8px 0 0;box-sizing:border-box;">
      <img style="width:30%;" src="<?php echo base_url("assets/img/".$this->func->globalset("logo")); ?>" />
    </div>
    <div style="padding:20px;background:#fff;border:1px solid #e8e8e8;width:100%;box-sizing:border-box;">
      <?php echo $content; ?>
    </div>
    <div style="background:#e8e8e8;border:1px solid #e8e8e8;color:#939598;padding:16px 24px;text-align:center;width:100%;border-radius:0 0 8px 8px;box-sizing:border-box;">
      <small>Pesan dibuat otomatis dan dikirimkan oleh sistem <?=$this->func->globalset("nama")?>, semua balasan yang ditujukan ke alamat email ini tidak akan kami
      respon, untuk menghubungi Admin kami silahkan kirimkan email ke <a href="mailto:<?=$this->func->globalset("email")?>"><?=$this->func->globalset("email")?></a>.<p/>
      <b><?=$this->func->globalset("nama")?> &copy; <?php echo date("Y"); ?></b></small>
    </div>
  </div>
</div>
