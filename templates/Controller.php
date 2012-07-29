<?php echo '<?php'."\n"; ?>

namespace Sea\App\Controllers;
use Sea\Core\Controller;

class <?php echo $name ?> extends Controller
{

<?php foreach($actions as $action): ?>
	public function <?php echo $action ?>()
	{
		
	}

<?php endforeach; ?>
}
