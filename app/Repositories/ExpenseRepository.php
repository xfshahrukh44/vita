<?php

namespace App\Repositories;

use App\Exceptions\Expense\AllExpenseException;
use App\Exceptions\Expense\CreateExpenseException;
use App\Exceptions\Expense\UpdateExpenseException;
use App\Exceptions\Expense\DeleteExpenseException;
use App\Models\Expense;

abstract class ExpenseRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }
    
    public function create(array $data)
    {
        try 
        {
            $expense = $this->model->create($data);
            
            return [
                'expense' => $this->find($expense->id)
            ];
        }
        catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
    
    public function delete($id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find expense',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'expense' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteExpenseException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find expense',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'expense' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateExpenseException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $expense = $this->model::with('area', 'ledgers', 'payments', 'stock_ins')->find($id);
            if(!$expense)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find expense',
                ];
            }
            return [
                'success' => true,
                'expense' => $expense,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('area')->get();
        }
        catch (\Exception $exception) {
            throw new AllExpenseException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllExpenseException($exception->getMessage());
        }
    }

    public function search_expenses($query)
    {
        // foreign fields

        // search block
        $expenses = Expense::where('detail', 'LIKE', '%'.$query.'%')
                        ->orWhere('type', 'LIKE', '%'.$query.'%')
                        ->orWhere('amount', 'LIKE', '%'.$query.'%')
                        ->orWhere('detail', 'LIKE', '%'.$query.'%')
                        ->paginate(env('PAGINATION'));

        return $expenses;
    }

    public function fetch_expenses(array $data)
    {
        // check for wildcard(*)
        if($data['type'] == 'All'){
            // all expenses
            $expenses = Expense::where('date', '>=', $data['date_from'])
                                ->where('date', '<=', $data['date_to'])
                                ->orderBy('date', 'ASC')
                                ->get();
        }
        else{
            // all filtered expenses
            $expenses = Expense::where('type', $data['type'])
                                ->where('date', '>=', $data['date_from'])
                                ->where('date', '<=', $data['date_to'])
                                ->orderBy('date', 'ASC')
                                ->get();
        }

        // calculate total
        $total = 0;
        foreach($expenses as $expense){
            $total += $expense->amount;
        }

        return [
            'expenses' => $expenses,
            'total' => $total
        ];
    }
}
