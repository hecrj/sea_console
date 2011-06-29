<?php echo '<?php'."\n"; ?>

# <?=$name?> class
class <?=$name?> extends Controller
{

<?php foreach($data as $action): ?>
	public function <?=$action?>()
	{
		
	}

<?php endforeach; ?>
}

?>
