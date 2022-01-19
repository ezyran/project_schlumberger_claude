import { Component, OnInit } from '@angular/core';
import { Select, Store } from '@ngxs/store';
import { Observable } from 'rxjs';
import { Product } from 'src/app/Modules/product/Models/product';
import { ClearProducts, RemoveProduct } from '../../Cart/Actions/cart.action';
import { CartState } from '../../Cart/States/cart-state';

@Component({
  selector: 'app-cart',
  templateUrl: './cart.component.html',
  styleUrls: ['./cart.component.css']
})
export class CartComponent implements OnInit {

  constructor(private store: Store) { }

  RemoveProductFromCart(product: Product): void {
    this.store.dispatch(new RemoveProduct(product));
  }

  ClearCart(): void {
    this.store.dispatch(new ClearProducts());
  }

  @Select(CartState.getProducts) lstProductsInCart?: Observable<Product[]>
  ngOnInit(): void {
  }

}
