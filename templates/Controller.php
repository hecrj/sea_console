<?php echo '<?php'."\n"; ?>

namespace App\Controllers;
use Sea\Controller;

class <?php echo $name ?> extends Controller
{

<?php foreach($actions as $action): ?>
	public function <?php echo $action ?>()
	{
		
	}

<?php endforeach; ?>
}
