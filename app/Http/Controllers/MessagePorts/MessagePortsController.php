<?php

namespace App\Http\Controllers\MessagePorts;

use App\MessagePort;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MessagePortsController extends Controller
{

    /**
     * MessagePortsController constructor.
     */
    public function __construct()
    {
    }

    public function getMessagePortsPaginated()
    {
        try {
            $paginate_num = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $messagePorts = MessagePort::where('is_deleted', '=', false)->orderBy('id', 'DESC')->paginate($paginate_num);
            return response()->json(['status' => true, 'message' => 'message-ports successfully fetched', 'result' => $messagePorts], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function getMessagePortList()
    {
        try {
            $messagePorts = MessagePort::where('is_deleted', '=', false)->orderBy('id', 'DESC')->get();
            return response()->json(['status' => true, 'message' => 'message-ports successfully fetched', 'result' => $messagePorts], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            $credential = request()->only('name', 'api_key', 'campaign_id', 'sms_port_id', 'campaign_name', 'sms_port_name');
            $rules = [
                'name' => 'required|max:255',
                'api_key' => 'required',
                'campaign_id' => 'required|max:255',
                'sms_port_id' => 'required',
            ];
            $validator = Validator::make($credential, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => true, 'error' => $error], 500);
            }
            $newMessagePort = new MessagePort();
            $newMessagePort->name = $credential['name'];
            $newMessagePort->api_key = $credential['api_key'];
            $newMessagePort->campaign_id = $credential['campaign_id'];
            $newMessagePort->sms_port_id = $credential['sms_port_id'];
            $newMessagePort->campaign_name = isset($credential['campaign_name']) ? $credential['campaign_name'] : null;
            $newMessagePort->sms_port_name = isset($credential['sms_port_name']) ? $credential['sms_port_name'] : null;
            if ($newMessagePort->save()) {
                return response()->json(['status' => true, 'message' => 'message-port successfully created', 'result' => $newMessagePort], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }

    public function update() {
        try {
            $credential = request()->only('id', 'name', 'api_key', 'campaign_id', 'sms_port_id', 'campaign_name', 'sms_port_name', 'is_connected', 'is_active');
            $rules = [
                'id' => 'required'
            ];
            $validator = Validator::make($credential, $rules);

            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => true, 'error' => $error], 500);
            }
            $oldMessagePort = MessagePort::where('id', '=', $credential['id'])->first();
            if($oldMessagePort instanceof MessagePort) {
                $oldMessagePort->name = isset($credential['name']) ? $credential['name'] : $oldMessagePort->name;
                $oldMessagePort->api_key = isset($credential['api_key']) ? $credential['api_key'] : $oldMessagePort->api_key;
                $oldMessagePort->campaign_id = isset($credential['campaign_id']) ? $credential['campaign_id'] : $oldMessagePort->campaign_id;
                $oldMessagePort->sms_port_id = isset($credential['sms_port_id']) ? $credential['sms_port_id'] : $oldMessagePort->sms_port_id;
                $oldMessagePort->sms_port_id = isset($credential['sms_port_id']) ? $credential['sms_port_id'] : $oldMessagePort->sms_port_id;
                $oldMessagePort->campaign_name = isset($credential['campaign_name']) ? $credential['campaign_name'] : $oldMessagePort->campaign_name;
                $oldMessagePort->sms_port_name = isset($credential['sms_port_name']) ? $credential['sms_port_name'] : $oldMessagePort->sms_port_name;
                $oldMessagePort->is_connected = isset($credential['is_connected']) ? $credential['is_connected'] : $oldMessagePort->is_connected;
                $oldMessagePort->is_active = isset($credential['is_active']) ? $credential['is_active'] : $oldMessagePort->is_active;
                if ($oldMessagePort->update()) {
                    return response()->json(['status' => true, 'message' => 'message-port successfully updated', 'result' => $oldMessagePort], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Whoops! unable to find the message port for update', 'result'=>null], 500);
                }
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops! something went wrong', 'error' => $exception->getMessage()], 500);
        }
    }
    public function delete($id)
    {
        try {
            $oldMessagePort = MessagePort::where('id', '=', $id)->first();
            if ($oldMessagePort instanceof MessagePort) {
                $oldMessagePort->name = $oldMessagePort->name . ' | DELETED PORT |' . str_random(10);
                $oldMessagePort->api_key = str_random(30);
                $oldMessagePort->is_deleted = true;
                $oldMessagePort->is_active = false;
                if ($oldMessagePort->save()) {
                    return response()->json(['status' => true, 'message' => 'message-port successfully deleted'], 200);
                } else {
                    return response()->json(['status' => false, 'error' => 'Whoops! failed to delete the message-port'], 500);
                }
            } else {
                return response()->json(['status' => false, 'error' => 'Whoops! unable to find the message-port information'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'error' => 'Whoops! something went wrong', 'message' => $exception->getMessage()], 500);
        }
    }
}
