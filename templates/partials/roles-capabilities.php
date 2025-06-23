<div class="roko-card">
	<div class="roko-card-header">
	<h3 class="roko-card-title">User Roles &amp; Capabilities</h3>
	<p class="roko-card-subtitle">Manage roles and their capabilities</p>
	</div>
	<div class="roko-card-body">
	<table class="roko-table">
		<thead>
		<tr><th>Role</th><th>Capabilities</th><th>Action</th></tr>
		</thead>
		<tbody>
		<?php
		foreach ( get_editable_roles() as $role_key => $role_info ) :
			$caps = array_keys( $role_info['capabilities'] );
			?>
		<tr>
			<td><?php echo esc_html( $role_info['name'] ); ?></td>
			<td><?php echo esc_html( implode( ', ', $caps ) ); ?></td>
			<td><button class="roko-button-small" data-role="<?php echo esc_attr( $role_key ); ?>">Edit</button></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	</div>
</div>

