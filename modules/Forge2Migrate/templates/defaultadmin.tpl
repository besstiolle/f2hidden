<style>
	a.ormbutton {
	    color: #232323;
	    display: inline-block;
	    line-height: 26px;
	    margin: 10px 0;
	    padding: 1px 8px 2px 20px;
	    position: relative;
	    text-decoration: none;
	}
	a.ormbutton .ui-icon {
	    left: 0;
	    position: absolute;
	    top: 6px;
	}
</style>

{if !empty($error)}<h2 style='color:#FF0000;'>{$error}</h2>{/if}

<h3>Current state : </h3>

<table class="pagetable" cellspacing="0">
	<thead>
		<tr>
			<th>Entity</th><th>Elements before migration</th><th>Elements migrated</th>
		</tr>
	</thead>
	<tbody>
		{foreach $counters as $name => $counter}
			<tr><td>{$name}</td><td>{$counter.old}</td><td>{$counter.new}</td></tr>
		{/foreach}
	</tbody>
</table>

<a href="{$startReinit}" onclick="return confirm('It will erase data from the previous migrations. Are you sure ?')" 
	class="ormbutton ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-check"></span>(Re)initiate the tables
</a>
<a href="{$startMigration}" onclick="return confirm('It will start the migration and will take some time. Are you sure ?')" 
	class="ormbutton ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-check"></span>Migrate the data
</a>