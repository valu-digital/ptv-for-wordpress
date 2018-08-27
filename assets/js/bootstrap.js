/**
 * The internal dependencies.
 */
import {registerFieldComponent} from "lib/registry";
import SearchableSetField from "components/SearchableSetField";
import MarkdownField from "components/MarkdownField";

registerFieldComponent("ptv_searchable_set", SearchableSetField);
registerFieldComponent("ptv_markdown", MarkdownField);
