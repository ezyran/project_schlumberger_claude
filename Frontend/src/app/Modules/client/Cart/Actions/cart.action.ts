import { Product } from "src/app/Modules/product/Models/product";

export class AddProduct {
    static readonly type = "[Product] Add";

    constructor(public payload: Product) {}
}

export class RemoveProduct {
    static readonly type = "[Product] Remove";

    constructor(public payload: Product) {}
}

export class ClearProducts {
    static readonly type = "[Product] Clear";

    constructor() {}
}