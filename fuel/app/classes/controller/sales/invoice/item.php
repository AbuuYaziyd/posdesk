<?php

class Controller_Sales_Invoice_Item extends Controller_Authenticate
{
	public function action_index()
	{
		$pos_invoice_items = Model_Cashier_Invoice_Item::find('all');
		echo json_encode($pos_invoice_items);
	}

	public function action_search()
    {
		$data = [];
		
        if (Input::is_ajax())
        {
            $item = Model_Product_Item::query()
												->where(
													array('id' => Input::post('item_id'))
												)
												->get_one();
			$data['invoice_item'] = Model_Sales_Invoice_Item::forge(
				array(
					'item_id' => $item->id,
					'quantity' => 1, // default is 1
					'unit_price' => $item->unit_price,
					'amount' => $item->unit_price, // initial total is equal to unit_price
					'invoice_id' => null,
					'tax_rate' => $item->tax_rate,
					'discount_percent' => $item->discount_percent,
					'description' => $item->item_name,
				));
			$data['item'] = $item;
			$data['row_id'] = Input::post('next_row_id');
            return View::forge('cashier/invoice/item/_form', $data);
        }
	}

	public function action_create()
    {
        if (Input::is_ajax())
        {
			$data['row_id'] = Input::post('next_row_id');
            return View::forge('cashier/invoice/item/_form', $data);
        }
	}
	
	public function action_read()
    {
		$item = '';
		
        if (Input::is_ajax())
        {
            $item = Model_Product_Item::query()
									->where(
										array('id' => Input::post('item_id'))
									)
									->get_one()
									->to_array();
		}
		
		return json_encode($item);
	}

	public function action_delete()
	{
        if (Input::is_ajax())
        {
			$id = Input::post('id');

			if ($pos_invoice_item = Model_Cashier_Invoice_Item::find($id)) 
			{
				try {
					$pos_invoice_item->delete();
				}
				catch (Exception $e) {
					return $e->getMessage();
				}

				$msg = 'Deleted sales item #'.$id;
			}
			else
			{
				$msg = 'Could not delete sales item #'.$id;
			}
			
			return json_encode($msg);
		}
	}

}
