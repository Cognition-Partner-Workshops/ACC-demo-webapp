# ACC Demo Platform Runbook (start / stop the demo VM)

Starting and stopping the Magento "ACC environment" VM is fully automated by two Devin skills,
`acc-env-start` and `acc-env-stop`. You don't need to run any Docker or Magento commands by hand —
just talk to Devin.

> **Note:** The VM has a permanent AWS **Elastic IP** (`18.225.243.219`), so the storefront URL
> never changes across restarts. The EC2 instance itself is started/stopped manually by the operator
> in the AWS console; the skills handle everything on the VM.

## Start the demo

1. Make sure the operator has started the EC2 instance in AWS.
2. On the VM, start the Devin CLI:
   ```bash
   devin
   ```
3. Ask it to start the environment, e.g.:
   > start the ACC demo environment

That's it — the `acc-env-start` skill brings the containers up and verifies the storefront is healthy
at http://18.225.243.219/.

## Stop the demo

1. On the VM, start the Devin CLI:
   ```bash
   devin
   ```
2. Ask it to stop the environment, e.g.:
   > stop the ACC demo environment

The `acc-env-stop` skill gracefully stops the containers (preserving all data). When it's done, the
operator can stop the EC2 instance in AWS.
