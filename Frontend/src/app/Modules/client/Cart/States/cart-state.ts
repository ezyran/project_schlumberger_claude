import { Action, Selector, State, StateContext } from "@ngxs/store";
import { Product } from "src/app/Modules/product/Models/product";

import { AddProduct, ClearProducts, RemoveProduct } from "../Actions/cart.action";
import { CartStateModel } from "../Models/cart-state.model";

@State<CartStateModel>({
    name: "products",
    defaults: {
        products: []
    }
})
export class CartState {
    @Selector()
    static getNbProducts(state: CartStateModel) {
        return state.products?.length;
    }
    @Selector()
    static getProducts(state: CartStateModel) {
        console.log("GetProducts = " + state.products);
        return state.products;
    }

    @Action(AddProduct)
    add(
        { getState, patchState }: StateContext<CartStateModel>,
        { payload }: AddProduct
    ) {
        const state = getState();
        let tmpProducts = state.products;
        tmpProducts.push(payload);
        console.log("AddProduct = " + tmpProducts);
        patchState({
            products: tmpProducts
        });
    }

    @Action(RemoveProduct)
    remove(
        { getState, patchState }: StateContext<CartStateModel>,
        { payload }: RemoveProduct
    ) {
        const state = getState();
        let tmpProducts = state.products;
        tmpProducts.splice(tmpProducts.indexOf(payload), 1);
        patchState({
            products: tmpProducts
        });
    }

    @Action(ClearProducts)
    clear(
        { getState, patchState }: StateContext<CartStateModel>
    ) {
        const state = getState();
        let tmpProducts = state.products;
        tmpProducts = [];
        patchState({
            products: tmpProducts
        });
    }


}