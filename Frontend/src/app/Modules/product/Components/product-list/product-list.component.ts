import { Component, OnInit } from '@angular/core';
import { FormControl } from '@angular/forms';
import { Store } from '@ngxs/store';
import { AddProduct } from 'src/app/Modules/client/Cart/Actions/cart.action';
import { ClientService } from 'src/app/Modules/client/Services/client.service';
import { Product } from '../../Models/product';
import { ProductService } from '../../Services/product.service';

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.css']
})
export class ProductListComponent implements OnInit {

  public mProducts!: Array<Product>;
  productMinPrice = new FormControl("");
  productMaxPrice = new FormControl("");
  productName = new FormControl("");

  constructor(private productService: ProductService, public clientService: ClientService, private store: Store) { }

  ngOnInit(): void {
    this.productService.GetAllProducts().subscribe(res => this.mProducts = res);
  }

  //#region Business Logic
  GetProductsFromCriteria(): void {
    let tempProductList: Product[] = [];
    let finalProductList: Product[] = [];
    
    // Récupération de la liste complète des produits
    this.productService.GetAllProducts().subscribe(res => {
      tempProductList = res;
      // Itération sur les produits pour sélectionner ceux qui répondent au critères
      tempProductList.forEach(product => {
        console.log(product);
        if (product.price >= this.productMinPrice.value || this.productMinPrice.value === "") {
          console.log("  - Prix min = OK");
          if (product.price <= this.productMaxPrice.value || this.productMaxPrice.value === "") {
            console.log("  - Prix max = OK");
            if (product.name.includes(this.productName.value.trim()) || this.productName.value.trim() === "") {
              console.log("  - Nom = OK");
              finalProductList.push(product);
            }
          }
        }
      });
      this.mProducts = finalProductList;
    });
  }
  //#endregion

  //#region Form events
  OnResetButtonClick(): void {
    this.productMinPrice.reset("");
    this.productMaxPrice.reset("");
    this.productName.reset("");
  }

  OnValidateButtonClick(): void {
    console.log(this.productMinPrice.value);
    console.log(this.productMaxPrice.value);
    console.log(this.productName.value);
    this.GetProductsFromCriteria();
  }

  AddProductToCart(product: Product): void {
    this.store.dispatch(new AddProduct(product));
  }
  //#endregion
}
