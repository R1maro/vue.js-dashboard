<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{

    public function get_all_invoice()
    {
        $invoice = Invoice::with('customer')->orderBy('id', 'DESC')->get();
        return response()->json([
            'invoices' => $invoice
        ]);
    }

    public function search_invoice(Request $request)
    {
        $search = $request->get('s');
        if ($search != null) {
            $invoices = Invoice::with('customer')->where('id', 'LIKE', "%$search%")->get();
            return response()->json([
                'invoices' => $invoices
            ], 200);
        } else
            return $this->get_all_invoice();
    }

    public function create_invoice(Request $request)
    {
        $counter = Counter::where('key', 'invoice')->first();
        $random = Counter::where('key', 'invoice')->first();

        $invoice = Counter::where('key', 'invoice')->first();
        if ($invoice) {
            $invoice = $invoice->id + 1;
            $counters = $counter->value + $invoice;
        } else {
            $counters = $counter->value;
        }
        $formData = [
            'number' => $counter->prefix . $counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'term_and_conditions' => 'Default Term And Conditions',
            'items' => [
                [
                    'product_id' => null,
                    'product' => null,
                    'unit_price' => null,
                    'quantity' => 1,
                ]
            ]
        ];
        return response()->json($formData);
    }


    public function add_invoice(Request $request)
    {

        $invoicedata['sub_total'] = $request->input('subtotal');
        $invoicedata['total'] = $request->input('total');
        $invoicedata['customer_id'] = $request->input('customer_id');
        $invoicedata['number'] = $request->input('number');
        $invoicedata['date'] = $request->input('date');
        $invoicedata['due_date'] = $request->input('due_date');
        $invoicedata['discount'] = $request->input('discount');
        $invoicedata['reference'] = $request->input('reference');
        $invoicedata['terms_and_conditions'] = $request->input('terms_and_conditions');
        return Invoice::create($invoicedata);

        $invoiceitem = $request->input('invoice_item');
        foreach (json_decode($invoiceitem) as $item) {
            $itemdata['product_id'] = $item->id;
            $itemdata['invoice_id'] = $invoice->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;

            InvoiceItem::create($itemdata);
        }
    }

    public function show_invoice($id)
    {
        $invoice = Invoice::with(['customer', 'invoice_items.product'])->find($id);

        // Calculate the subtotal
        $sub_total = $invoice->invoice_items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });


        // Assuming discount is a percentage
        $discount = $invoice->invoice_items->sum(function ($item) {
            return $item->quantity * $item->discount;
        });


        // Calculate the total
        $total = $sub_total - $discount;

        // Add these calculated fields to the invoice object
        $invoice->sub_total = $sub_total;
        $invoice->total = $total;

        return response()->json([
            'invoice' => $invoice
        ], 200);

    }

    public function edit_invoice($id)
    {
        $invoice = Invoice::with(['customer', 'invoice_items.product'])->find($id);

        // Calculate the subtotal
        $sub_total = $invoice->invoice_items->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });


        // Assuming discount is a percentage
        $discount = $invoice->invoice_items->sum(function ($item) {
            return $item->quantity * $item->discount;
        });

        // Calculate the total
        $total = $sub_total - $discount;

        // Add these calculated fields to the invoice object
        $invoice->sub_total = $sub_total;
        $invoice->total = $total;

        return response()->json([
            'invoice' => $invoice
        ], 200);
    }

    public function delete_invoice_items($id)
    {
        $invoiceitem = InvoiceItem::findOrFail($id);
        $invoiceitem->delete();
    }

    public function update_invoice(Request $request, $id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->number = $request->number;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->discount = $request->discount;
        $invoice->reference = $request->reference;
        $invoice->terms_and_conditions = $request->terms_and_conditions;

        $invoice->save();


        $invoice->invoice_items()->delete();

        $invoice_items = json_decode($request->invoice_items, true);
        Log::info('Decoded invoice items:', ['invoice_items' => $invoice_items]);
        if (is_array($invoice_items)) {
            foreach ($invoice_items as $item) {
                $itemdata = [
                    'product_id' => $item['product_id'],
                    'invoice_id' => $invoice->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ];
                InvoiceItem::create($itemdata);
            }
        } else {
            return response()->json(['error' => 'Invalid invoice items data'], 400);
        }

    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
