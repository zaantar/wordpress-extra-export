<?php

namespace ToolsetExtraExport;

/**
 * Provides correct migration handlers for different data sections.
 *
 * It is possible to add more sections via the toolset_extra_export_get_migration_handler filter.
 *
 * @since 1.0
 */
class Migration_Handler_Factory {

	/**
	 * Get the migration handler for given section.
	 *
	 * @param string $section_name
	 * @return MigrationHandler\IMigration_Handler Migration handler object, if it was possible to select one.
	 * @throws \RuntimeException when no migration handler is available.
	 */
	public static function get( $section_name ) {

		$migration_handler = null;

		switch( $section_name ) {
			case Data_Section::APPEARANCE_MENU:
			case Data_Section::APPEARANCE_WIDGETS:
				// not implemented yet
				break;
            case Data_Section::APPEARANCE_CUSTOMIZE:
                return new MigrationHandler\Customizer();
			case Data_Section::SETTINGS_READING:
				return new MigrationHandler\Settings_Reading();
		}

		/**
		 * toolset_extra_export_get_migration_handler
		 *
		 * Allows for overriding a selected migration handler or extending the list of supported sections.
		 *
		 * @param MigrationHandler\IMigration_Handler|null $migration_handler Current migration handler object, if one was selected.
		 * @param string $section_name Name of the section.
		 * @return MigrationHandler\IMigration_Handler
		 * @since 1.0
		 */
		$migration_handler = apply_filters( 'toolset_extra_export_get_migration_handler', $migration_handler, $section_name );

		if( ! $migration_handler instanceof MigrationHandler\IMigration_Handler ) {
			throw new \RuntimeException( sprintf( __( 'Migration handler for %s not available.', 'toolset-ee' ), $section_name ) );
		}

		return $migration_handler;
	}



}