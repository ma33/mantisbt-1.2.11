<?php
# MantisBT - a php based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

# How to Use
# add to config_inc.php
# if($g_due_date_update_threshold != NOBODY && $g_due_date_view_threshold != NOBODY ) {
# 	array_push($g_custom_group_actions, array(	'action' => 'EXT_UPDATE_DUEDATE','label' => 'actiongroup_menu_update_due_date'));
# }

	/**
	 * @package MantisBT
	 * @copyright Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	 * @copyright Copyright (C) 2002 - 2012  MantisBT Team - mantisbt-dev@lists.sourceforge.net
	 * @link http://www.mantisbt.org
	 */
	 
	/**
	 * Prints the title for the custom action page.
	 */
	function action_update_duedate_print_title() {
        echo '<tr class="form-title">';
        echo '<td colspan="2">';
        echo lang_get( 'update_duedate_title' );
        echo '</td></tr>';
	}

	/**
	 * Prints the field within the custom action form.  This has an entry for
	 * every field the user need to supply + the submit button.  The fields are
	 * added as rows in a table that is already created by the calling code.
	 * A row has two columns.
	 */
	function action_update_duedate_print_fields() {
		# Due Date

		$t_date_to_display = '';

		echo '<tr class="row-1" valign="top"><td class="category">', lang_get( 'due_date' ), '</td>';
		echo '<td>','<input ', helper_get_tab_index(), ' type="text" id="due_date" name="due_date" size="20" maxlength="16" value="', $t_date_to_display, '">';

		date_print_calendar();
		date_finish_calendar( 'due_date', 'trigger');
		
		echo '</td>';
		
		echo '<tr><td colspan="2"><center><input type="submit" class="button" value="' . lang_get( 'actiongroup_menu_update_duedate' ) . ' " /></center></td></tr>';

	}

	/**
	 * Validates the action on the specified bug id.
	 *
	 * @returns true|array Action can be applied., ( bug_id => reason for failure )
	 */
	function action_update_duedate_validate( $p_bug_id ) {
		$t_failed_validation_ids = array();

		$t_update_severity_threshold = config_get( 'update_bug_threshold' );
		$t_bug_id = $p_bug_id;

		if (!get_duedate()) {
			$t_failed_validation_ids[$t_bug_id] = 'invalid date';
			return $t_failed_validation_ids;
		}

		if ( bug_is_readonly( $t_bug_id ) ) {
			$t_failed_validation_ids[$t_bug_id] = lang_get( 'actiongroup_error_issue_is_readonly' );
			return $t_failed_validation_ids;
		}

		if ( !access_has_bug_level( config_get( 'due_date_update_threshold' ), $t_bug_id ) ) {
			$t_failed_validation_ids[$t_bug_id] = lang_get( 'access_denied' );
			return $t_failed_validation_ids;
		}
		
		return true;
	}
	
	/**
	 * Executes the custom action on the specified bug id.
	 *
	 * @param $p_bug_id  The bug id to execute the custom action on.
	 * @returns true|array Action executed successfully., ( bug_id => reason for failure )
	 */
	function action_update_duedate_process( $p_bug_id ) {

		bug_set_field( $p_bug_id, 'due_date', get_duedate() );

		return true;
    }
    
    function get_duedate() {
		$f_duedate = gpc_get_string( 'due_date' );

		return strtotime( $f_duedate );
    }

