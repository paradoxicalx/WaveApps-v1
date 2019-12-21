<div class="box-body traffic-body">
  <div id="info-traffic"></div>
  <div class="row">
    <div class="col-md-8">
      <div class="chart chart-container" style="position: relative; height:250px;">
        <canvas id="TrafficChart"></canvas>
      </div>
    </div>
    <div class="col-md-4">
      <div class="box box-info">
        <div class="input-group ">
          <select id="ro-list" class="form-control select2" style="width:100%">
            <option value=""></option>
            <?php
            $query = sqlQuAssoc("SELECT * FROM wavenet.tb_devices_oid WHERE `deleted` = '0'");
            foreach ($query as $key) : $selected = ""; if ($key['onstart'] === "1") { $selected = "selected"; } ?>
              <option value='<?= $key['id'] ; ?>' <?= $selected ; ?>><?= $key['router-name'] ?> (<?= $key['board-name'] ?>)</option>
            <?php endforeach ?>
          </select>
          <span class="input-group-btn">
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-cog"></i></button>
            <ul class="dropdown-menu pull-right" role="menu">
              <li id="btn-new-device"><a href="#"><i class="fas fa-plus-circle"></i> New Device</a></li>
              <li id="device-setting"><a href="#"><i class="fas fa-wrench"></i> Setting</a></li>
            </ul>
          </span>
        </div>
      </div>
      <div class="box box-solid bg-aqua-gradient" id="bar-monitor">
        <?php $device = sqlQuAssoc("SELECT * FROM wavenet.tb_devices_oid WHERE `onstart` = '1'"); ?>
        <div class="box-body">
          <div class="row" id="bar-cpu">
            <?php if ($device[0]['cpu-count'] > 1) : ?>
              <?php for ($i=0; $i < $device[0]['cpu-count']; $i++) : ?>
                <div class="col-md-6">
                  <div class="progress-group" id="cpu-usage">
                    <span class="progress-text">CPU <?= $i ; ?></span>
                    <span class="progress-number" id="cpu<?= $i ; ?>">0%</span>
                    <div class="progress sm">
                      <div id="progress-cpu<?= $i ; ?>" class="progress-bar progress-bar-green" style="width: 0%"></div>
                    </div>
                  </div>
                </div>
              <?php endfor ?>
            <?php else : ?>
              <div class="col-md-12">
                <div class="progress-group" id="cpu-usage">
                  <span class="progress-text">CPU 0</span>
                  <span class="progress-number" id="cpu0">0%</span>
                  <div class="progress sm">
                    <div id="progress-cpu0" class="progress-bar progress-bar-green" style="width: 0%"></div>
                  </div>
                </div>
              </div>
            <?php endif ?>
          </div>
          <div class="progress-group" id="memory-usage">
            <span class="progress-text">Memory Usage</span>
            <span class="progress-number" id=mem-usage><b>0</b>/0</span>
            <div class="progress sm">
              <div id="progress-mem" class="progress-bar progress-bar-red" style="width: 0%"></div>
            </div>
          </div>
          <div class="progress-group">
            <span class="progress-text">Disk Usage</span>
            <span class="progress-number" id="disk-usage"><b>0</b>/0</span>
            <div class="progress sm">
              <div id="progress-disk" class="progress-bar progress-bar-green" style="width: 0%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="box-footer">
  <div class="row">
    <div class="col-sm-3 col-xs-6">
      <div class="description-block border-right">
        <span class="description-percentage text-red"><i class="fas fa-download"></i> DOWNLOAD</span>
        <h5 class="description-header" id="dl-total">0 B</h5>
        <span id="dl-average">0 B/day</span>
      </div>
    </div>
    <div class="col-sm-3 col-xs-6">
      <div class="description-block border-right">
        <span class="description-percentage text-blue"><i class="fas fa-upload"></i> UPLOAD</span>
        <h5 class="description-header" id="ul-total">0 B</h5>
        <span id="ul-average">0 B/day</span>
      </div>
    </div>
    <div class="col-sm-3 col-xs-6">
      <div class="description-block border-right">
        <span class="description-percentage"><i class="fas fa-history"></i> UPTIME</span>
        <h5 class="description-header" id="uptime-router"></h5>
        <span id="timezone-router"></span>
      </div>
    </div>
    <div class="col-sm-3 col-xs-6">
      <div class="description-block">
        <span class="description-percentage"><i class="fas fa-temperature-low"></i> TEMPERATURE</span>
        <h5 class="description-header" id="temperature">Unknown</h5>
        <span id="voltage">Unknown</span>
      </div>
    </div>
  </div>
</div>
