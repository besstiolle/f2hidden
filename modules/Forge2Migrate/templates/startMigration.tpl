<style>
	.prog_w{
		background: #ccc;
		font-size: 1.2em;
		height: 20px;
		text-align: center;
		width: 100%;
		position: relative;
		border-radius: 15px;
	}
	.prog{
		background: #aaa;
		height: 20px;
		position: absolute;
		border-radius: 15px;
	}
	.prog_t{
		height: 20px;
		position: absolute;
		width: 100%;
		color:#FFF;
	}
</style>


<div class='prog_w'>
	<div class='prog' style='width:{$ratio}%'></div>
	<div class='prog_t'>{$ratio} %</div>
</div>


{if $nextEntity == $entityPosition} 
	<p>Migration of entity {$entityName} from #{$startId} to #{$nextId} is done.</p>
	<p>Next Migration of entity {$entityName} from #{$nextId+1} is ready </p>
{else}
	<p>Migration of entity {$entityName} from #{$startId} to the end is done.</p>
	{if count($entityNameToMigrate) > $nextEntity}
		<p>Next Migration of entity {$entityNameToMigrate.$nextEntity} from #{$nextId} is ready</p>
	{else}
		<p>there is no more migration  Return to the <a href='{$returnLink}'>main page</a>.</p>
	{/if}
	
{/if}


{if count($entityNameToMigrate) > $nextEntity}
	<a href='{$nextLink}'>Continue the migration</a>
{/if}
