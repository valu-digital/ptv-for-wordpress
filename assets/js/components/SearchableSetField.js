/**
 * The external dependencies.
 */
import React from "react";
import PropTypes from "prop-types";
import {
    compose,
    withHandlers,
    branch,
    renderComponent,
    setStatic,
} from "recompose";
import Select from "react-select";
import "react-select/dist/react-select.css";

import Field from "fields/components/field";
import NoOptions from "fields/components/no-options";
import withStore from "fields/decorators/with-store";
import withSetup from "fields/decorators/with-setup";
import {TYPE_SET} from "fields/constants";

/**
 * Render a collection of checkbox inputs.
 *
 * @param  {Object}        props
 * @param  {Object}        props.name
 * @param  {Object}        props.field
 * @param  {Function}      props.isChecked
 * @param  {Function}      props.handleChange
 * @return {React.Element}
 */
export const SetField = ({name, field, isChecked, handleChange}) => {
    return (
        <Field field={field}>
            <Select
                multi
                name="form-field-name"
                value={field.options
                    .filter(isChecked)
                    .map(option => option.value)}
                options={field.options.map(option => ({
                    value: option.value,
                    label: option.name,
                }))}
                onChange={handleChange}
            />
            {/* Must render hidden fields for Carbon Fields to pick up the items */}
            {field.options
                .filter(isChecked)
                .map((option, index) => (
                    <input
                        key={index}
                        type="hidden"
                        name={`${name}[${index}]`}
                        value={option.value}
                    />
                ))}
        </Field>
    );
};

/**
 * Validate the props.
 *
 * @type {Object}
 */
SetField.propTypes = {
    name: PropTypes.string,
    field: PropTypes.shape({
        id: PropTypes.string,
        value: PropTypes.arrayOf(PropTypes.string),
        options: PropTypes.arrayOf(
            PropTypes.shape({
                name: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
                value: PropTypes.oneOfType([
                    PropTypes.string,
                    PropTypes.number,
                ]),
            }),
        ),
    }),
    isChecked: PropTypes.func,
};

/**
 * The enhancer.
 *
 * @type {Function}
 */
export const enhance = compose(
    /**
     * Connect to the Redux store.
     */
    withStore(),
    /**
     * Render "No-Options" component when the field doesn't have options.
     */
    branch(
        /**
         * Test to see if the "No-Options" should be rendered.
         */
        ({field: {options}}) => options && options.length,
        /**
         * Render the actual field.
         */
        compose(
            /**
             * Attach the setup hooks.
             */
            withSetup(),
            /**
             * Pass some handlers to the component.
             */
            withHandlers({
                handleChange: ({field, setFieldValue}) => options => {
                    setFieldValue(
                        field.id,
                        options.map(option => option.value),
                    );
                },
                isChecked: ({field}) => option =>
                    field.value.indexOf(String(option.value)) > -1,
            }),
        ),
        /**
         * Render the empty component.
         */
        renderComponent(NoOptions),
    ),
);

export default setStatic("type", [TYPE_SET])(enhance(SetField));
