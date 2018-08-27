import React from "react";
import ReactDOM from "react-dom";
import {Plugin} from "prosemirror-state";

class MenuView {
    constructor(items, editorView, container) {
        this.container = container;
        this.editorView = editorView;
        this.items = items.map(item => ({
            ...item,
            command: () => {
                const actualCommand = item.command();

                if (typeof actualCommand === "function") {
                    actualCommand(
                        this.editorView.state,
                        this.editorView.dispatch,
                        this.editorView,
                    );
                }
            },
        }));

        this.dom = document.createElement("div");
        this.dom.className = "menubar";
        this.update();
    }

    update() {
        // this.items.forEach(({command, dom}, i) => {
        //     let active = command(this.editorView.state, null, this.editorView);
        //     console.log(i, active);
        // });

        const Container = this.container;
        const MenuWrap = ({items}) => (
            <Container>{items.map(item => item.render(item))}</Container>
        );

        ReactDOM.render(<MenuWrap items={this.items} />, this.dom);
    }

    destroy() {
        this.dom.remove();
    }
}

export const createReactMenu = options => {
    return new Plugin({
        view(editorView) {
            let menuView = new MenuView(
                options.items,
                editorView,
                options.container,
            );
            editorView.dom.parentNode.insertBefore(
                menuView.dom,
                editorView.dom,
            );
            return menuView;
        },
    });
};
