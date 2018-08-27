/**
 * The external dependencies.
 */
import React from "react";
import PropTypes from "prop-types";
import debounce from "lodash/debounce";
import g from "glamorous";
import {compose, withHandlers, setStatic} from "recompose";
import "./editor.css";

/**
 * The internal dependencies.
 */
import Field from "fields/components/field";
import withStore from "fields/decorators/with-store";
import withSetup from "fields/decorators/with-setup";
import {
    TYPE_TEXTAREA,
    TYPE_HEADER_SCRIPTS,
    TYPE_FOOTER_SCRIPTS,
} from "fields/constants";

import {EditorView} from "prosemirror-view";
import {EditorState} from "prosemirror-state";
import {history, undo, redo} from "prosemirror-history";
import {keymap} from "prosemirror-keymap";
import {toggleMark, baseKeymap} from "prosemirror-commands";
import {
    schema,
    defaultMarkdownParser,
    defaultMarkdownSerializer,
} from "prosemirror-markdown";

import {createReactMenu} from "./prosemirror-menu";

const View = g.div({
    display: "flex",
    position: "relative",
    flexDirection: "column",
});

const ProseMirrorContainer = g(View)({
    // hmm
});

const Button = g.button({
    minWidth: 25,
    height: 25,
    marginRight: 5,
});

class ProseMirrorEditor extends React.Component {
    menuItems = [
        {
            command: () => {
                const href = window.prompt("Linkki");

                if (href === null) {
                    return;
                }

                return toggleMark(schema.marks.link, {href});
            },

            render: ({command}) => {
                return (
                    <Button
                        onClick={e => {
                            e.preventDefault();
                            command();
                        }}
                    >
                        A
                    </Button>
                );
            },
        },

        {
            command: () => toggleMark(schema.marks.strong),
            render: ({command}) => {
                return (
                    <Button
                        onClick={e => {
                            e.preventDefault();
                            command();
                        }}
                    >
                        B
                    </Button>
                );
            },
        },

        {
            command: () => toggleMark(schema.marks.em),
            render: ({command}) => {
                return (
                    <Button
                        onClick={e => {
                            e.preventDefault();
                            command();
                        }}
                    >
                        I
                    </Button>
                );
            },
        },

        {
            render: () => {
                return (
                    <Button
                        onClick={e => {
                            e.preventDefault();
                            if (this.props.onHide) {
                                this.props.onHide();
                            }
                        }}
                    >
                        Koodi
                    </Button>
                );
            },
        },
    ];

    createProseMirrorEditor(el) {
        const view = new EditorView(el, {
            state: EditorState.create({
                doc: defaultMarkdownParser.parse(this.props.value),
                plugins: [
                    history(),
                    keymap(baseKeymap),
                    keymap({"Mod-z": undo, "Mod-y": redo}),
                    createReactMenu({
                        container: "div",
                        items: this.menuItems,
                    }),
                ],
            }),

            dispatchTransaction: tr => {
                view.updateState(view.state.apply(tr));

                if (typeof this.props.onChange === "function") {
                    this.props.onChange(this);
                }
            },
        });
        return view;
    }

    getMarkdownContent = () => {
        if (this.editor) {
            return defaultMarkdownSerializer.serialize(this.editor.state.doc);
        }

        return "";
    };

    componentWillUnmount() {
        if (this.editor) {
            this.editor.destroy();
        }
    }

    handleRef = el => {
        if (!this.el) {
            this.el = el;
            this.editor = this.createProseMirrorEditor(el);
        }
    };

    render() {
        return (
            <ProseMirrorContainer>
                <div ref={this.handleRef} />
            </ProseMirrorContainer>
        );
    }
}

class MultiEditor extends React.Component {
    state = {wysiwyg: true};

    debouncedProseMirrorChange = debounce(editor => {
        this.props.onChange({target: {value: editor.getMarkdownContent()}});
    }, 1000);

    render() {
        const {field} = this.props;

        return (
            <View>
                {this.state.wysiwyg && (
                    <ProseMirrorEditor
                        value={field.value}
                        onHide={() => this.setState({wysiwyg: false})}
                        onChange={this.debouncedProseMirrorChange}
                    />
                )}

                {!this.state.wysiwyg && (
                    <g.Div width={200} marginBottom={2}>
                        <Button
                            onClick={e => {
                                e.preventDefault();
                                this.setState({wysiwyg: true});
                            }}
                            onChange={this.debouncedProseMirrorChange}
                        >
                            Rikas editori
                        </Button>
                    </g.Div>
                )}

                <g.Textarea
                    display={this.state.wysiwyg ? "none !important" : "block"}
                    id={field.id}
                    name={this.props.name}
                    value={field.value}
                    rows={field.rows}
                    disabled={!field.ui.is_visible}
                    onChange={this.props.onChange}
                    {...field.attributes}
                />
            </View>
        );
    }
}

/**
 * Render a multiline text input field.
 *
 * @param  {Object}        props
 * @param  {String}        props.name
 * @param  {Object}        props.field
 * @param  {Function}      props.handleChange
 * @return {React.Element}
 */
export const TextareaField = ({name, field, handleChange}) => {
    return (
        <Field field={field}>
            <div>
                <MultiEditor
                    name={name}
                    field={field}
                    onChange={handleChange}
                />
            </div>
        </Field>
    );
};

/**
 * Validate the props.
 *
 * @type {Object}
 */
TextareaField.propTypes = {
    name: PropTypes.string,
    field: PropTypes.shape({
        id: PropTypes.string,
        value: PropTypes.string,
        rows: PropTypes.number,
        attributes: PropTypes.object,
    }),
    handleChange: PropTypes.func,
};

/**
 * The enhancer.
 *
 * @type {Function}
 */
export const enhance = compose(
    withStore(),
    withSetup(),

    /**
     * The handlers passed to the component.
     */
    withHandlers({
        handleChange: ({field, setFieldValue}) => ({target: {value}}) =>
            setFieldValue(field.id, value),
    }),
);

export default setStatic("type", [
    TYPE_TEXTAREA,
    TYPE_HEADER_SCRIPTS,
    TYPE_FOOTER_SCRIPTS,
])(enhance(TextareaField));
