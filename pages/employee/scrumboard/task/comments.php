<!-- Comments -->
<div style="display: flex;gap: 5px;">
            
<a class="add-comment-icon tooltip" style="display:block">
    <svg class="h-6 w-6 transition-all duration-300" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="12" fill="#dff0d8"></circle>
        <text x="12" y="16" font-size="14" text-anchor="middle" fill="#3c763d"><?php echo $resCom->num_rows ?></text>
    </svg>
    <span class="tooltiptext2"><?php echo $rowComment['user'].' :'."\n". $rowComment['value'] ?></span>
</a>

</div>