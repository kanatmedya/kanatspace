<td role="gridcell" class="fc-daygrid-day fc-day fc-day-<?php echo strtolower($gun_adi); ?> fc-day-past" data-date="<?php echo $gun_tarihi; ?>" aria-labelledby="fc-dom-<?php echo $gun; ?>">
    <div class="fc-daygrid-day-frame fc-scrollgrid-sync-inner">
        <div class="fc-daygrid-day-top">
            <a id="fc-dom-<?php echo $gun; ?>" class="fc-daygrid-day-number" aria-label="<?php echo $gun; ?> <?php echo date('F Y', $gun_tarih); ?>">
                <?php echo $gun; ?>
            </a>
        </div>
        <div class="fc-daygrid-day-events">
            <div class="fc-daygrid-day-bottom" style="margin-top: 0px;">
                <!-- Günün etkinlik ve görevleri buraya eklenecek -->
            </div>
        </div>
        <div class="fc-daygrid-day-bg"></div>
    </div>
</td>
