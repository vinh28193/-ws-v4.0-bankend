https://scotch.io/tutorials/implementing-remote-procedure-calls-with-grpc-and-protocol-buffers
$ npm install -g grpcli 
grpcc --proto proto/user.proto --address 206.189.94.203:50053 -i
grpcc --proto proto/accounting.proto --address 206.189.94.203:50054 -i

grpcli -f proto/work_leave.proto --ip=127.0.0.1 --port=50050 -i

grpcli -f proto/accounting.proto --ip=206.189.94.203 --port=50054 -i


https://github.com/grpc/grpc-node/issues/337
